<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Detail Tiket Konser</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body class="bg-gray-100 flex items-center justify-center min-h-screen">
        <section class="container mx-auto">
            <div
                class="bg-white max-w-md mx-auto border border-gray-200 rounded-lg shadow-lg overflow-hidden"
            >
                <!-- Header -->
                <div class="bg-blue-600 p-6 text-center text-white">
                    <h1 class="text-2xl font-bold">{{$data->event->name}}</h1>
                    <p class="text-sm font-light mt-1">Detail Tiket Konser</p>
                </div>

                <!-- Body -->
                <div class="p-6">
                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">
                            Nama Pemesan:
                        </h2>
                        <p class="text-gray-900 text-lg mt-1">
                            {{$data->customer->name}}
                        </p>
                    </div>
                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">
                            ID Ticket:
                        </h2>
                        <p class="text-gray-900 text-lg mt-1">
                            {{$data->ticket_code}}
                        </p>
                    </div>
                </div>

                <!-- Footer with QR Code and Event Date -->
                <div class="p-6 border-t border-gray-200 text-center">
                    <p class="text-sm text-gray-600">
                        Tunjukkan tiket ini di pintu masuk
                    </p>
                    <div class="mt-4">
                        <img
                            src="/path/to/qr-code.png"
                            alt="QR Code"
                            class="w-24 h-24 mx-auto"
                        />
                    </div>
                    <p class="text-sm text-gray-500 mt-2">
                        Tanggal Event: {{$data->event->date}}
                    </p>
                </div>
            </div>
        </section>
    </body>
</html>
