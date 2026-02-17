<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        {{-- uncomment below link after live project uncomment and  --}}
        <link href="{{asset('build/assets/app-fdc1baea.css')}}" rel="stylesheet">

        <!-- Styles -->
        @livewireStyles
    </head>
    <body>
        <div class="font-sans text-gray-400 dark:text-gray-700 antialiased">
            {{ $slot }}
        </div>

        <script src="{{asset('build/assets/app-ddee773b.js')}}"></script>
        @livewireScripts
    </body>
</html>
