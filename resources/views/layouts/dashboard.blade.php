<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/GaTal-logo.png') }}">
    <title>@yield('title', 'Dashboard - Hospital Information System')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation Component -->
        <x-navigation />

        <!-- Main Content -->
        <main class="ml-68">
            <div class="px-8 py-8">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
