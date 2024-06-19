<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@100..900&family=IBM+Plex+Mono:ital@0;1&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0" />

        <!-- Scripts -->
        @routes

        @vite([
            'resources/css/style.min.css',
            'resources/bootstrap/bootstrap-icons.css',
            'resources/css/fontawesome.min.css',
            'resources/css/allfa.css',

            'resources/js/app.js',
            'resources/js/theme.bundle.min2.js',
            'resources/js/allfa.js',
        ])
    </head>
    <body>
        <div class="position-absolute z-3 w-auto h-auto end-0 top-0 mt-4 me-4">
            <div class="dropdown">
              <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" id="bs-theme" type="button"
                aria-expanded="false" data-bs-toggle="dropdown" data-bs-display="static">
                <span class="theme-icon-active d-flex align-items-center">
                  <span class="material-symbols-rounded align-middle"></span>
                </span>
              </a>
              <!--:Dark Mode Options:-->
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bs-theme" style="--bs-dropdown-min-width: 9rem;">
                <li class="mb-1">
                  <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="light">
                    <span class="theme-icon d-flex align-items-center">
                      <span class="material-symbols-rounded align-middle me-2">
                        lightbulb
                      </span>
                    </span>
                    Light
                  </button>
                </li>
                <li class="mb-1">
                  <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark">
                    <span class="theme-icon d-flex align-items-center">
                      <span class="material-symbols-rounded align-middle me-2">
                        dark_mode
                      </span>
                    </span>
                    Dark
                  </button>
                </li>
                <li>
                  <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="auto">
                    <span class="theme-icon d-flex align-items-center">
                      <span class="material-symbols-rounded align-middle me-2">
                        invert_colors
                      </span>
                    </span>
                    Auto
                  </button>
                </li>
              </ul>
            </div>
        </div>

        @include('components.loading')

        @include('components.decoration-bg')

        <div class="d-flex flex-column flex-root">
            <div class="page d-flex flex-row flex-column-fluid z-1 position-relative">
                <main class="page-content overflow-hidden ms-0 d-flex flex-column flex-row-fluid">
                    <div class="content p-1 d-flex flex-column-fluid position-relative">
                        <div class="container py-4">
                          <div class="row h-100 align-items-center justify-content-center">
                            <div class="col-sm-8 col-11 col-lg-5 col-xl-4">
                                <div class="card card-body">
                                    {{ $slot }}
                                </div>
                            </div>
                          </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <footer class="pb-3 pb-lg-5 px-3 px-lg-6">
          <div class="container-fluid px-0">
            <span class="d-block lh-sm small text-body-secondary text-end">
                &copy; {{ date('Y') }}. {{ __('general.brend_title') }}
            </span>
          </div>
        </footer>
    </body>
</html>
