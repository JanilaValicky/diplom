<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">

  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ Vite::asset('resources/assets/images/pflogo.jpg') }}" type="image/ico">
    @yield('styles')
    @yield('scripts')

    @vite([
        'resources/css/aos.css',
        'resources/css/theme.min.css',

        'resources/js/app.js',
        'resources/js/theme.bundle.min.js',
    ])

    <!--Google fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,400&family=Source+Serif+Pro:ital@0;1&display=swap" rel="stylesheet">

    <title>@yield('title')</title>
  </head>

  <body>
    @include('public.layouts.loading')
    <!--begin:Header-->
    <header class="header-transparent sticky-fixed">
        @include('public.layouts.partials.header')
    </header>
    <!--end:header-->

    <!--begin:main content-->
    <main class="main-content d-grid" id="main-content">
      @yield('main')
    </main>
    <!--end:main content-->

    <!--begin:Footer-->
    <footer>
      @include('public.layouts.partials.footer')
    </footer>
    <!--end:Footer-->

    @yield('bottom-scripts')
  </body>
</html>
