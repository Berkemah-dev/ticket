<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
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

    public function bookingStore(Request $request)
    {
        // dd(intval($request->input('price')));
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

        // dd($data['name']);
        // dd($data['email']);
        $params = array(
            'transaction_details' => array(
                'order_id' => 'order-' . time() . '-' . Str::random(5),
                'gross_amount' => intval($request->input('price')),
            ),
            'customer_details' => array(
                'first_name' => $data['name'],
                'email' => $data['email'],
            )
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        $order->snap_token = $snapToken;
        $order->save();
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
