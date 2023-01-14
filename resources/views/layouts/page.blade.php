<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>


        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100;300;400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    </head>
    <body>
        <v-app id="app">
            <div class="light2 am-fill-height">
                <x-navigation state='open' :header="true" :menu-button="true" />
                <v-main>
                    @yield('content')
                </v-main>
            </div>
            {{-- <x-navigation state='open' :menu-button="true" />
            <v-container fluid class="light2 am-fill-height pa-0">

                <v-main>
                    @yield('content')
                </v-main>

            </v-container> --}}
        </v-app>
        @yield('js')
    </body>

</html>
