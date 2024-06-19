<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite([
            'resources/css/aos.css',
            'resources/css/app.css',
            'resources/css/theme.min.css',

            'resources/js/app.js',
            'resources/js/theme.bundle.min.js',
        ])
    <!--Google fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,400&family=Source+Serif+Pro:ital@0;1&display=swap"
        rel="stylesheet">
    <title>@yield('title')</title>
</head>

<body>
@include('public.layouts.loading')

<!--Main content-->
<main class="main-content d-grid" id="main-content">
    <section class="position-relative overflow-hidden">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-lg-8 mx-auto text-center position-relative">

                    <div class=" position-relative z-1">

                        <div class="text-danger mb-5">
                            <img src="{{ Vite::asset('resources/assets/images/error.svg') }}" class="width-18x mx-auto"
                                 alt="{{ __('error.error_image') }}">
                        </div>
                        <h1 class="display-1 mb-2">@yield('code')</h1>
                        <h2 class="mb-4">@yield('message')</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
</body>

</html>
