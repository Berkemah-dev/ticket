<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />

        <title>Booking Tiket Konser</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
        />
    </head>

    <body>
        <section class="container mt-4">
            <h1>Pembayaran</h1>
            <div class="row">
                <div class="col-lg-12 mt-4">
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <h3>{{$data->event->name}}</h3>
                        </div>
                        <div class="card-body">
                            <div class="p-2">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input
                                            type="hidden"
                                            name="id_customer"
                                            value="{{$data->customer->id}}"
                                        />
                                        <input
                                            type="hidden"
                                            name="id_event"
                                            value="{{$data->event->id}}"
                                        />
                                        <a
                                            >Nama Pemesan :
                                            {{$data->customer->name}}</a
                                        ><br />
                                        <a
                                            >Jumlah Dibayar : Rp.
                                            {{number_format($data->price,0,',','.')}}</a
                                        ><br />
                                        <a>Tanggal : {{$data->created_at}}</a
                                        ><br />
                                        <a>Invoice : {{ $data->invoice }}</a>
                                        <hr />
                                    </div>
                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                        id="pay-button"
                                    >
                                        Konfirmasi Pembayaran
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
        <script
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"
        ></script>
        <script type="text/javascript">
            document.getElementById("pay-button").onclick = function () {
                event.preventDefault();
                // SnapToken acquired from previous step
                snap.pay("{{ $data->snap_token }}", {
                    // Optional
                    onSuccess: function (result) {
                        window.location.href =
                            "{{ route('checkout.success', $data->id) }}";
                    },
                    // Optional
                    onPending: function (result) {
                        /* You may add your own js here, this is just example */ document.getElementById(
                            "result-json"
                        ).innerHTML += JSON.stringify(result, null, 2);
                    },
                    // Optional
                    onError: function (result) {
                        console.log(result);
                        /* You may add your own js here, this is just example */
                        document.getElementById("result-json").innerHTML +=
                            JSON.stringify(result, null, 2);
                    },
                });
            };
        </script>
    </body>
</html>
