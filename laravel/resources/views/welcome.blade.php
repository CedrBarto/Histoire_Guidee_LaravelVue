<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Histoire Guidée</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #5a1414 0%, #200505 100%);
            color: #fff;
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        
        .relative {
            position: relative;
        }
        
        .max-w-7xl {
            max-width: 80rem;
        }
        
        .mx-auto {
            margin-left: auto;
            margin-right: auto;
        }
        
        .px-6 {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        
        .py-4 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        
        .text-right {
            text-align: right;
        }
        
        .auth-links a, .auth-link-btn {
            color: #ffffff;
            text-decoration: none;
            margin-left: 1rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: all 0.2s;
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
            font-size: inherit;
        }
        
        .auth-links a:hover, .auth-link-btn:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 2rem;
        }
        
        .logo {
            width: 5rem;
            margin-bottom: 2rem;
        }
        
        h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .description {
            font-size: 1.25rem;
            max-width: 600px;
            margin: 0 auto 2rem;
            line-height: 1.6;
        }
        
        .start-btn {
            display: inline-block;
            background-color: #5a1414;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            text-decoration: none;
            transition: background-color 0.2s;
        }
        
        .start-btn:hover {
            background-color: #7a1c1c;
        }
    </style>
</head>

<body>
    <div class="relative">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="text-right auth-links">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/home') }}">Accueil</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline-block">
                            @csrf
                            <button type="submit" class="auth-link-btn">Déconnexion</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>

    <div class="container">
        <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="logo" onerror="this.src='https://laravel.com/img/logomark.min.svg'; this.onerror=null;">
        <h1>Histoire Guidée</h1>
        <p class="description">
            Bienvenue dans notre application d'histoires interactives. Connectez-vous ou inscrivez-vous pour commencer votre aventure.
        </p>
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/home') }}" class="start-btn">Commencer l'aventure</a>
            @else
                <a href="{{ route('login') }}" class="start-btn">Connexion</a>
            @endauth
        @endif
    </div>
</body>

</html>