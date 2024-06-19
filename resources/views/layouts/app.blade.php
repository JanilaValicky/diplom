<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PollyForms') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@100..900&family=IBM+Plex+Mono:ital@0;1&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0" />

        @routes
        <!-- Scripts&Styles -->
        @routes

        @vite([
            'resources/css/style.min.css',
            'resources/css/fontawesome.min.css',
            'resources/css/allfa.css',
            'resources/bootstrap/bootstrap-icons.css',

            'resources/js/theme.bundle.min2.js',
            'resources/js/allfa.js',
            'resources/js/app.js',
            'resources/js/logout.js',
        ])

    </head>
    <body>
        @include('components.loading')
        <div class="d-flex flex-column flex-root">
            <div class="page d-flex flex-row flex-column-fluid">
                @include('layouts.navigation')
                <div class="page-content d-flex flex-column flex-row-fluid">
                    <!-- Page Heading -->
                    @if (isset($header))
                        <header class="navbar mb-3 px-3 px-lg-6 align-items-center page-header navbar-expand navbar-light">
                            {{ $header }}
                        </header>
                    @endif

                    <!-- Page Content -->
                    <main>
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
