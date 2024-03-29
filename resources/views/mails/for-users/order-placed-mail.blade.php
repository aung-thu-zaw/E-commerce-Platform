<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet">

    <script src="https://kit.fontawesome.com/18c274e5f3.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900&display=swap" rel="stylesheet" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.css" rel="stylesheet" />

    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js','resources/css/app.css'])



</head>

<body class="font-sans antialiased">

    <div class="bg-gray-50 min-h-screen flex items-center justify-center px-3">
        <div class="lg:w-[900px] border bg-white shadow">
            <div class="px-10 py-5">
                <h3 class="font-bold w-full text-center my-5 text-slate-800 text-2xl">GLOBAL E-COMMERCE</h3>

                <p class="text-center text-md font-semibold text-slate-600 mb-5">
                    Your order is placed!
                </p>


                <p class="text-sm font-bold mb-2">Hi, Aung Thu Zaw,</p>
                <p class="text-sm  mb-2 ">We are excited for you to receive your order <span class="text-blue-600">{{
                        $order->order_no
                        }}</span> and will
                    notify
                    you once its on its way. If you have ordered from multiple sellers, your items will be delivered in
                    separate packages. We hope you had a great shopping experience! You can check your order status
                    here.</p>

                <div class="flex items-center justify-center">
                    <a href="{{ route('my-orders.show',$order->id) }}"
                        class="font-bold text-sm px-10 uppercase py-3 shadow bg-blue-600 text-white rounded-sm my-5 ">
                        Your Order
                    </a>
                </div>

                <p class="text-sm  mb-2 ">
                    Please note, we are unable to change your delivery address once your order is
                    placed.
                </p>
                <p class="text-sm  mb-2 ">
                    Here's a confirmation of what you bought in your order.
                </p>

                <hr>

                <h4 class="uppercase font-bold text-slate-700 text-md mt-5 mb-3">Delivery Details</h4>

                <div class="mb-5">
                    <p class="text-sm font-bold text-slate-600">Name : {{ $order->deliveryInformation->name }}</p>
                    <p class="text-sm font-bold text-slate-600">Email : {{ $order->deliveryInformation->email }}</p>
                    <p class="text-sm font-bold text-slate-600">Phone : {{ $order->deliveryInformation->phone }}</p>
                    <p class="text-sm font-bold text-slate-600">Address : {{ $order->deliveryInformation->address }}</p>
                </div>

                <hr>

                <h4 class="uppercase font-bold text-slate-700 text-md mt-5 mb-3">Order Details</h4>

                @foreach ($order->orderItems as $item)
                <div class="flex flex-col md:flex-row items-start">
                    <img src="{{ $item->product->image }}" alt="" class=" h-28 object-cover mr-3 mb-5">
                    <div class="mb-5">
                        <p class="text-md font-bold text-slate-700 mb-1">
                            {{ $item->product->name }}
                        </p>
                        @if ($item->product->discount)
                        <p class="text-sm font-bold text-orange-600">Price: $ {{ $item->product->discount }}</p>
                        @else
                        <p class="text-sm font-bold text-orange-600">Price: $ {{ $item->product->price }}</p>
                        @endif
                        <p class="text-sm font-bold text-orange-600">Quantity: {{ $item->qty }}</p>
                        <p class="text-sm font-bold text-orange-600">Total: $ {{ $item->price }}</p>
                        <p class="text-sm font-bold text-slate-600">Sold by {{ $item->product->shop->shop_name }}</p>
                        {{-- <p class="text-sm font-bold text-slate-600">Estimated delivery between 02 May-06 May</p>
                        --}}
                    </div>
                </div>
                @endforeach



                <hr>

                <h4 class="uppercase font-bold text-slate-700 text-md mt-5 mb-3 text-right">Payment Details</h4>

                <p class="text-sm font-bold text-slate-600 text-right">Subtotal : $ {{ $order->total_amount }}</p>
                {{-- <p class="text-sm font-bold text-slate-600 text-right">Shipping Fee : 5$</p> --}}

                <hr class="my-3">

                <p class="text-sm font-bold text-slate-600 text-right">Total : $ {{ $order->total_amount }}</p>
                <p class="text-sm font-bold text-slate-600 text-right ">Payment Type : <span class="capitalize">{{
                        $order->payment_type }}</span>
                </p>
            </div>


            <div class="w-full bg-blue-700 text-white text-sm my-5 p-3">
                <p class="font-bold text-center">STILL HAVE QUESTIONS?
                    <a href="#" class="underline hover:animate-bounce">
                        GO TO HELP CENTER
                    </a>
                </p>
            </div>

            <div class="flex items-center justify-center my-3">
                <a href="#" class="text-slate-700 hover:text-blue-600 font-bold text-2xl mx-3">
                    <i class="fa-brands fa-facebook"></i>
                </a>
                <a href="#" class="text-slate-700 hover:text-pink-600 font-bold text-2xl mx-3">
                    <i class="fa-brands fa-instagram"></i>
                </a>
                <a href="#" class="text-slate-700 hover:text-sky-600 font-bold text-2xl mx-3">
                    <i class="fa-brands fa-twitter"></i>
                </a>
                <a href="#" class="text-slate-700 hover:text-red-600 font-bold text-2xl mx-3">
                    <i class="fa-brands fa-youtube"></i>
                </a>
            </div>

            <p class="text-center text-sm font-bold text-slate-600 mb-5">This is an automatically generated e-mail.
                Please do
                not reply to this e-mail.</p>

        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.js"></script>

</body>

</html>