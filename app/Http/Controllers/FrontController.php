<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use App\Models\Customer;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TotalAffiliate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class FrontController extends Controller
{
    public function index()
    {
        $data = Event::orderBy('id', 'desc')->get();
        return view('frontend.index', compact('data'));
    }

    public function booking($id)
    {
        $data = Event::findorfail($id);
        return view('frontend.booking', compact('data'));
    }

    public function referral(Request $request) {
        try {
            $validated = $request->validate([
                'referral_code' => 'required|exists:affiliates,referral_code',
            ]);

            $affiliate = Affiliate::where('referral_code', $validated['referral_code'])->firstOrFail();

            $totalAffiliate = TotalAffiliate::create([
                'affiliator_id' => $affiliate->affiliator_id,
                'affiliate_id' => $affiliate->id,
                'status' => 'pending'
            ]);

            return response()->json([
                'message' => 'Referral code is valid',
                'affiliate' => $totalAffiliate,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan, silakan coba lagi.',
            ], 500);
        }
    }

    public function bookingStore(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'birthdate' => 'required',
        ]);

        $data = new Customer();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->address = $request->address;
        $data->phone = $request->phone;
        $data->birthdate = $request->birthdate;
        $data->save();


        $order = new Order();
        $order->id_customer = $data->id;
        $order->id_event = $request->id_event;
        $order->status = 'pending';
        $order->invoice = 'inv-'.time().'-'.Str::random(8);


        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $grossAmount = $request->input('price');
        $grossAmount = floor($grossAmount);  // Membulatkan ke bawah ke angka bulat

        // Pastikan bahwa gross_amount adalah integer dan minimal 1
        if ($grossAmount < 1) {
            $grossAmount = 1;
        }
        $params = array(
            'transaction_details' => array(
                'order_id' => 'order-' . time() . '-' . Str::random(5),
                'gross_amount' => $grossAmount,
            ),
            'customer_details' => array(
                'first_name' => $data['name'],
                'email' => $data['email'],
            )
        );
        $order->price = $grossAmount;
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        $order->snap_token = $snapToken;
        $order->save();

        $idTotalAffiliate = $request->input('idTotalAffiliate');
        if ($idTotalAffiliate) {
            $totalAffiliate =TotalAffiliate::where('id', $idTotalAffiliate)->firstOrFail();
            $totalAffiliate['order_id'] = $order->id;
            $totalAffiliate->save();
        }

        return redirect()->route('front.booking.payment',$order->id)->with('message', 'Sukses. Silahkan lakukan pembayaran.');
    }

    public function payment($id)
    {
        // $event = $order->event;
        // $order = $order;
        $data = Order::with('customer','event')->findorfail($id);
        return view('frontend.payment', compact('data'));
    }

    public function success(Request $request, $id){
        $order = Order::with('customer','event')->findorfail($id);
        $order['status'] = 'success';
        $order->save();

        $usedRefferal = TotalAffiliate::where('order_id', $order->id)->where('status', 'pending')->first();

        if ($usedRefferal) {
            $usedRefferal->status = 'success';
            $usedRefferal->save();
        }

        // TotalAffiliate::where('id_customer', $order->id_customer)
        // ->where('order_id', '!=', $order->id)
        // ->where('status', 'pending')
        // ->delete();

        $data = new Ticket();
        $data->ticket_code = Str::random(8);
        $data->id_customer = $order->id_customer;
        $data->id_event = $order->id_event;
        $data->status = 0;
        $data->save();

        return redirect(route('front.ticket',  $data->id))->with('message', 'Simpan nomor tiket untuk melakukan check in.');
    }

    public function paymentStore(Request $request)
    {

        $data = new Ticket();
        $data->ticket_code = Str::random(8);
        $data->id_customer = $request->id_customer;
        $data->id_event = $request->id_event;
        $data->status = 0;
        $data->save();

        return redirect(route('front.ticket', ['id' => $data->id]))->with('message', 'Simpan nomor tiket untuk melakukan check in.');;
    }

    public function ticketDetail($id)
    {
        $data = Ticket::findorfail($id);
        return view('frontend.ticket', compact('data'));
    }

    public function printTiket(Request $request,$id){
        $data = Ticket::where('id', $id)->firstOrFail();

        if ($request->get('export') == 'pdf') {
            $pdf = Pdf::loadView('frontend.pdf.ticket', ['data' => $data]);
            return $pdf->stream('ticket.pdf');

        }
        return view('frontend.ticket', compact('data','request'));
    }
}
