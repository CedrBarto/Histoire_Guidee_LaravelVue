<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Histoire Guidée')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
</head>
<body class="@yield('body-class')">
    <header>
        <nav>
            <div class="container">
                <a href="{{ route('stories.index') }}" class="logo">Histoire Guidée</a>
            </div>
        </nav>
    </header>

    <main class="container">
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} Histoire Guidée</p>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>