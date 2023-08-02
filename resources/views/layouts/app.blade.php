<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/fonts.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


    <!-- Scripts -->
    <script src="{{ asset('js/shortcut.js') }} "></script>
    <script src="{{ asset('js/app.js') }}" defer></script>

    @if ($expiration < $today)
        <style>
            .fondo-con-marca-de-agua {
                position: fixed;
                width: 100%;
                height: 100vh;
                background-image: linear-gradient(45deg, rgba(0, 0, 0, 0.2) 25%, transparent 25%),
                    linear-gradient(-45deg, rgba(0, 0, 0, 0.2) 25%, transparent 25%),
                    linear-gradient(45deg, transparent 75%, rgba(0, 0, 0, 0.2) 75%),
                    linear-gradient(-45deg, transparent 75%, rgba(0, 0, 0, 0.2) 75%);
                background-size: 20px 20px;
                background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
                color: black;
                /* Color del texto */
                font-size: 24px;
                text-align: center;
                padding-top: 150px;
				z-index: 100;
            }

            .fondo-con-marca-de-agua::before {
                content: "⚠️ LICENCIA VENCIDA ⚠️ LICENCIA VENCIDA ⚠️ LICENCIA VENCIDA ⚠️";
                /* position: absolute; */
                /* top: 50%; */
                /* left: 50%; */
				text-align: center;
                /* transform: translate(-50%, -50%) rotate(-45deg); */
                font-size: 5rem;
                font-weight: bold;
                color: rgba(241, 93, 93, 0.638);
				
                /* Color del texto de la marca de agua */
            }
        </style>
    @endif

</head>

<body>
    <div class="fondo-con-marca-de-agua">
        {{-- Este es un ejemplo de marca de agua con texto personalizado --}}
    </div>
    <div id="app">

        <div id="wrapper">

            <!-- Sidebar -->
            @component('components.navigation-bar')
            @endcomponent
            <main id="content-wrapper" class="d-flex flex-column">
                <div class="justify-content-center">
                    <router-view />
                </div>
                @component('components.footer')
                @endcomponent
            </main>
        </div>
    </div>

</body>

</html>
