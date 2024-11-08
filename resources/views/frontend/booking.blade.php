<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Booking Tiket Konser</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    />
    </head>

    <body>
        <section class="container mt-4">
            <h1>Booking Tiket Konser</h1>
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
                        <form
                            action="{{ route('front.booking.store') }}"
                            method="POST"
                            enctype="multipart/form-data"
                        >
                            @csrf
                            <div class="card-header">
                                <h3>{{$data->name}}</h3>
                                <br />
                                <a>Venue : {{$data->venue}}</a
                                ><br />
                                <p></p>
                                    Waktu :
                                    {{\Carbon\Carbon::parse($data->start_time)->translatedFormat('l, d M Y')}}
                                </p>
                                <br />
                                <p id="ticketPrice">
                                    Harga Tiket : Rp.
                                    {{number_format($data->price,0,',','.')}}
                                </p>
                                <input type="hidden" name="id_event" value="{{$data->id}}"/>
                                <input
                                    type="hidden"
                                    name="price"
                                    id="price"
                                    value="{{ $data->price }}"
                                />
                                <input
                                    type="hidden"
                                    name="idTotalAffiliate"
                                    id="idTotalAffiliate"

                                />
                            </div>
                            <div class="card-body">
                                <div class="p-4">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">Nama</div>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    name="name"
                                                    id=""
                                                    aria-describedby="helpId"
                                                    placeholder="Nama"
                                                    value="{!! old('name') !!}"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">Email</div>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <input
                                                    type="email"
                                                    class="form-control"
                                                    name="email"
                                                    id=""
                                                    aria-describedby="helpId"
                                                    placeholder="Email"
                                                    value="{!! old('email') !!}"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                Tanggal Lahir
                                            </div>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <input
                                                    type="date"
                                                    class="form-control"
                                                    name="birthdate"
                                                    aria-describedby="helpId"
                                                    placeholder="Tanggal Lahir"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">No. Hp</div>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    name="phone"
                                                    id=""
                                                    aria-describedby="helpId"
                                                    placeholder="No. Hp"
                                                    value="{!! old('phone') !!}"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">Alamat</div>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    name="address"
                                                    id=""
                                                    aria-describedby="helpId"
                                                    placeholder="Alamat"
                                                    value="{!! old('address') !!}"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                        <form id="formReferral">
                            <div class="input-group mb-3">
                                <input
                                    type="text"
                                    id="referral_code"
                                    name="referral_code"
                                    placeholder="Masukkan kode referral"
                                    aria-label="Recipient's username"
                                    aria-describedby="button-addon2"
                                />
                                <div class="input-group-append">
                                    <button
                                        class="btn btn-outline-secondary"
                                        type="submit"
                                        id="button-addon2"
                                    >
                                        Button
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <script
            src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
            crossorigin="anonymous"
        ></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
        <script>
            $("#formReferral").on("submit", function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                let diskon = parseInt($("#price").val()) * 0.10;
                let total = parseInt($("#price").val()) - diskon;

                total = Math.floor(total);

                if (total < 1) {
                    total = 1;
                }
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    type: "POST",
                    url: "{{ route('front.referral') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $("#price").val(total);
                        $("#idTotalAffiliate").val(response.affiliate.id);
                        $("#ticketPrice").html("Harga Tiket : Rp. " + total);
                },
                error: function(response) {

                },
                });
            });
        </script>
    </body>
</html>
