<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <script src="https://api-maps.yandex.ru/2.1/?apikey=5858e215-0f0c-41e2-a387-9d0943f3dee2&lang=ru_RU"
        type="text/javascript"></script>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="flex h-screen justify-center items-center">
        <div class="text-center" style="width: 360px">{{ $message }}</div>
    </div>
</body>

</html>
