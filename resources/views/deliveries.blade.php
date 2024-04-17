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
        <h1 class="text-4xl font-bold text-center py-8">{{ $zone->name }}</h1>
        <div class="">
            @foreach ($groups as $address => $group)
                <div class="text-xl p-2 bg-teal-900 rounded-lg text-white">
                    {{ $address }} ({{ count($group) }})
                </div>
                @foreach ($group as $purchase)
                    <div class="p-2 mb-4 min-h-screen flex flex-col items-center justify-center">
                        <div class="text-lg">
                            {{ $purchase->product->name }}
                        </div>
                        <div class="text-xs">
                            #ID{{ $purchase->id }} #TSKID{{ $purchase->task_id }}
                        </div>
                        <div class="text-center">
                            CODE {{ $purchase->delivery_pin }}
                        </div>
                        <div class="text-center">
                            <img src="/storage/{{ $purchase->delivery_qrcode }}" width="240" height="240"
                                class="inline-block" />
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
</body>

</html>
