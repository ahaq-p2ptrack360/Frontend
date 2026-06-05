<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<style>
    .fab-container {
            position: fixed;
            bottom: 25px;
            right: 85px;
            z-index: 1000;
        }

        .fab-button {
            background-color: #007bff;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            font-size: 24px;
            transition: transform 0.3s ease-in-out;
        }

        .fab-button.open {
            transform: rotate(45deg);
        }

        .fab-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            position: absolute;
            bottom: 70px;
            visibility: hidden;
            opacity: 0;
            transform: translateY(20px);
            transition: visibility 0.3s ease-in-out, opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        .fab-actions.show {
            visibility: visible;
            opacity: 1;
            transform: translateY(0);
        }

        .fab-action {
            background-color: #6c757d;
            color: white;
            width: 50px;
            height: 50px;
            right: -5px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            font-size: 20px;
            border: none;
            position: relative;
        }

        .fab-action:hover {
            background-color: #5a6268;
        }

        .fab-action[data-tooltip]:hover::before {
            content: attr(data-tooltip);
            position: absolute;
            right: 60px;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            white-space: nowrap;
            font-size: 14px;
            z-index: 1001;
        }

        .fab-actions.show .fab-action {
            transition: all 0.2s ease-out;
        }

        .fab-actions.show .fab-action:nth-child(1) {
            transition-delay: 0.05s;
        }

        .fab-actions.show .fab-action:nth-child(2) {
            transition-delay: 0.1s;
        }

</style>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
                    
        </main>
    </div>
</body>

</html>
