<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite('resources/js/app.js')
</head>

<body>
    <div class="w-screen mx-auto">
        <h1 class="text-4xl font-bold text-center py-8">Список на {{ now()->format('Y.m.d') }}</h1>
        <div class="w-80 mx-auto flex flex-col space-y-2">
            @foreach ($orders as $deliveryOrder)
                <a href="/generated/deliveries/{{ $deliveryOrder->pickpointZone->id }}-{{ now()->format('Y.m.d') }}"
                    class="bg-blue-600 rounded-xl py-3 px-4 text-white leading-none">{{ $deliveryOrder->pickpointZone->name }}</a>
            @endforeach
        </div>
    </div>
</body>

</html>
