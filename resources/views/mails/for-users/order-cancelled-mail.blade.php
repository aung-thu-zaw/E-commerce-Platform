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
                    Your package has been cancelled!
                </p>


                <p class="text-sm font-bold mb-2">Hi, Aung Thu Zaw,</p>
                <p class="text-sm  mb-2 ">Sorry to be the bearer of bad news, but your order #202334733321120 was
                    cancelled.</p>


                <h4 class="uppercase font-bold text-slate-700 text-md mt-5 mb-3">Order Details</h4>

                <div class="flex flex-col md:flex-row items-start">
                    <img src="https://images.pexels.com/photos/4566688/pexels-photo-4566688.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500"
                        alt="" class=" h-28 object-cover mr-3 mb-5">

                    <div class="mb-5">
                        <p class="text-md font-bold text-slate-700 mb-1">Lorem ipsum dolor sit amet consectetur,
                            adipisicing
                            elit.
                            Doloremque esse
                        </p>
                        <p class="text-sm font-bold text-orange-600">Price: 100$</p>
                        <p class="text-sm font-bold text-orange-600">Quantity: 1</p>
                        <p class="text-sm font-bold text-slate-600">Sold by One Stop</p>

                    </div>
                </div>

                <div class="flex flex-col md:flex-row items-start">
                    <img src="https://images.pexels.com/photos/4566688/pexels-photo-4566688.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500"
                        alt="" class=" h-28 object-cover mr-3 mb-5">

                    <div class="mb-5">
                        <p class="text-md font-bold text-slate-700 mb-1">Lorem ipsum dolor sit amet consectetur,
                            adipisicing
                            elit.
                            Doloremque esse
                        </p>
                        <p class="text-sm font-bold text-orange-600">Price: 100$</p>
                        <p class="text-sm font-bold text-orange-600">Quantity: 1</p>
                        <p class="text-sm font-bold text-slate-600">Sold by One Stop</p>

                    </div>
                </div>

                <div class="flex flex-col md:flex-row items-start">
                    <img src="https://images.pexels.com/photos/4566688/pexels-photo-4566688.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500"
                        alt="" class=" h-28 object-cover mr-3 mb-5">

                    <div class="mb-5">
                        <p class="text-md font-bold text-slate-700 mb-1">Lorem ipsum dolor sit amet consectetur,
                            adipisicing
                            elit.
                            Doloremque esse
                        </p>
                        <p class="text-sm font-bold text-orange-600">Price: 100$</p>
                        <p class="text-sm font-bold text-orange-600">Quantity: 1</p>
                        <p class="text-sm font-bold text-slate-600">Sold by One Stop</p>

                    </div>
                </div>

                <div class="flex flex-col md:flex-row items-start">
                    <img src="https://images.pexels.com/photos/4566688/pexels-photo-4566688.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500"
                        alt="" class=" h-28 object-cover mr-3 mb-5">

                    <div class="mb-5">
                        <p class="text-md font-bold text-slate-700 mb-1">Lorem ipsum dolor sit amet consectetur,
                            adipisicing
                            elit.
                            Doloremque esse
                        </p>
                        <p class="text-sm font-bold text-orange-600">Price: 100$</p>
                        <p class="text-sm font-bold text-orange-600">Quantity: 1</p>
                        <p class="text-sm font-bold text-slate-600">Sold by One Stop</p>

                    </div>
                </div>

                <div class="flex flex-col md:flex-row items-start">
                    <img src="https://images.pexels.com/photos/4566688/pexels-photo-4566688.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500"
                        alt="" class=" h-28 object-cover mr-3 mb-5">

                    <div class="mb-5">
                        <p class="text-md font-bold text-slate-700 mb-1">Lorem ipsum dolor sit amet consectetur,
                            adipisicing
                            elit.
                            Doloremque esse
                        </p>
                        <p class="text-sm font-bold text-orange-600">Price: 100$</p>
                        <p class="text-sm font-bold text-orange-600">Quantity: 1</p>
                        <p class="text-sm font-bold text-slate-600">Sold by One Stop</p>

                    </div>
                </div>


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