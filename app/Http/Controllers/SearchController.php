<?php

namespace App\Http\Controllers;

use App\Imports\PurchaseGeneratorImport;
use App\Models\Product;
use App\Models\WbPickpoints;
use App\Services\Purchase\ImportExcelService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SearchController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function send(Request $request)
    {
        $product = Product::where('remote_id', $request->product_code)->first();

        // if ($product) {
        //     $productArray = [
        //         'count' => 1,
        //         'keywords' => '',
        //         ...$product->toArray()
        //     ];

        //     $productArray['price'] = $productArray['price'] / 100;

        //     return response()->json([
        //         'product' => $productArray
        //     ]);
        // }

        $response = Http::get('https://card.wb.ru/cards/detail?appType=1&curr=rub&dest=-1257786&nm='.$request->product_code);
        $responseData = $response->json();

        if (! isset($responseData['data']['products'][0])) {
            return response()->json([
                'message' => 'Товар не найден',
            ], 404);
        }

        $productData = $responseData['data']['products'][0];

        if (! $product) {
            $product = new Product();
        }

        $product->remote_id = $productData['id'];
        $product->price = $productData['salePriceU'];
        $product->name = $productData['name'];
        $product->brand = $productData['brand'];
        $product->image = '';

        $product->save();

        if (count($productData['sizes']) == 1 && $productData['sizes'][0]['name'] == '') {
            $productData['sizes'] = [];
            $productSize = ['id' => '', 'name' => ''];
        } else {
            $productSize = [
                'id' => $productData['sizes'][0]['optionId'],
                'name' => $productData['sizes'][0]['name'],
            ];
        }

        $productArray = [
            'quantity' => 1,
            'keywords' => '',
            'address' => '',
            ...$product->toArray(),
            'size' => $productSize,
            'gender' => ['id' => 0, 'name' => 'Нет'],
            'purchase_at' => null,
            'to_decline' => false,
            'sizes' => array_map(function ($element) {
                return [
                    'id' => $element['optionId'],
                    'name' => $element['name'],
                ];
            }, $productData['sizes']),
            'errors' => [],
        ];

        $productArray['price'] = $productArray['price'] / 100;

        return response()->json([
            'product' => $productArray,
        ]);
    }

    public function pickpoints()
    {
        $resp = [];
        foreach (WbPickpoints::limit(100000)->get() as $pickpoint) {
            $resp[] = [
                'type' => 'Feature',
                'id' => $pickpoint->pickpoint_id,
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => explode(',', $pickpoint->coordinates),
                ],
                'properties' => [
                    'balloonContentWorkTime' => $pickpoint->work_time,
                    'balloonContentAddress' => $pickpoint->address,
                    //    "clusterCaption" => "<strong><s>Еще</s> одна</strong> метка",
                    'hintContent' => $pickpoint->address,
                ],
            ];
        }

        return response()->json($resp);
    }

    public function generate(Request $request): JsonResponse
    {

        $product = Product::where('remote_id', $request->productCode)->first();

        $response = Http::get('https://card.wb.ru/cards/detail?appType=1&curr=rub&dest=-1257786&nm='.$request->productCode);
        $responseData = $response->json();

        if (! isset($responseData['data']['products'][0])) {
            return response()->json([
                'message' => 'Товар не найден',
            ], 404);
        }

        $productData = $responseData['data']['products'][0];

        if (! $product) {
            $product = new Product();
        }

        $product->remote_id = $productData['id'];
        $product->price = $productData['salePriceU'];
        $product->name = $productData['name'];
        $product->brand = $productData['brand'];
        $product->image = '';

        $product->save();

        if (count($productData['sizes']) == 1 && $productData['sizes'][0]['name'] == '') {
            $productData['sizes'] = [];
            $productSize = ['id' => '', 'name' => ''];
        } else {
            $productSize = [
                'id' => $productData['sizes'][0]['optionId'],
                'name' => $productData['sizes'][0]['name'],
            ];
        }

        $productArray = [
            'quantity' => 1,
            'keywords' => '',
            'address' => '',
            ...$product->toArray(),
            'size' => $productSize,
            'gender' => ['id' => 0, 'name' => 'Нет'],
            'purchase_at' => null,
            'to_decline' => false,
            'sizes' => array_map(function ($element) {
                return [
                    'id' => $element['optionId'],
                    'name' => $element['name'],
                ];
            }, $productData['sizes']),
            'errors' => [],
        ];

        $productArray['price'] = $productArray['price'] / 100;

        $total = 0;
        $keywords = $request->keywords;

        $purchases = [];

        foreach ($request->ranges as $range) {
            $purchaseAt = Carbon::createFromTimeString($range['date']);
            $kwIdx = 0;

            for ($i = 0; $i < $range['counter']; $i++) {
                if ($kwIdx >= count($keywords)) {
                    $kwIdx = 0;
                }

                if ($keywords[$kwIdx]['counter'] == 0) {
                    $flag = false;

                    for ($j = 0; $j < count($keywords); $j++) {
                        if ($keywords[$j]['counter'] > 0) {
                            $kwIdx = $j;
                            $flag = true;
                        }
                    }

                    if (! $flag) {
                        continue;
                    }
                }

                $purchaseAt->setTimeFromTimeString('00:00');

                $randomHour = rand($range['startTime']['hours'], $range['endTime']['hours']);

                if ($randomHour == $range['endTime']['hours'] && $range['endTime']['minutes'] == 0) {
                    $randomHour = $randomHour - 1;
                }

                $purchaseAt->addHours($randomHour);

                if ($randomHour == $range['startTime']['hours']) {
                    $randomMinutes = rand($range['startTime']['minutes'], 59);
                } elseif ($randomHour == $range['endTime']['hours']) {
                    $randomMinutes = rand(0, $range['endTime']['minutes']);
                } else {
                    $randomMinutes = rand(0, 59);
                }

                $purchaseAt->addMinutes($randomMinutes);

                $purchases[] = [
                    ...$productArray,
                    'keywords' => $keywords[$kwIdx]['value'],
                    'address' => $request->addresses[rand(0, count($request->addresses) - 1)]['value'],
                    'purchase_at' => $purchaseAt->toDateTimeLocalString(),
                ];

                $keywords[$kwIdx]['counter']--;

                $kwIdx++;
            }
        }

        // foreach ($keywords as $keyword) {
        //     $total += $keyword['counter'];

        //     for ($i = 0; $i < $keyword['counter']; $i++) {
        //         $randomDate = $request->ranges[rand(0, count($request->ranges) - 1)]['date'];

        //         $purchases[] = [
        //             ...$productArray,
        //             'keywords' => $keyword['value'],
        //             'address' => $request->addresses[rand(0, count($request->addresses) - 1)]['value'],
        //             'purchase_at' => Carbon::createFromTimeString($randomDate)
        //                 ->subHours(rand(6, 12))
        //                 ->subMinutes(rand(1, 55)),
        //         ];
        //     }
        // }

        $sortedPurchases = collect($purchases)->sortBy(function ($obj, $key) {
            return $obj['purchase_at'];
        });

        return response()->json([
            'purchases' => [...$sortedPurchases->toArray()],
        ]);
    }

    public function importExcel(Request $request, ImportExcelService $importExcelService)
    {
        $rows = Excel::toCollection(new PurchaseGeneratorImport, $request->file);

        $result = match (count($rows)) {
            1 => $importExcelService->importSingleProduct($rows[0]),
            2 => $importExcelService->importMultipleProduct($rows[0], $rows[1]),
            default => null
        };

        if (! $result) {
            return response()->json([
                'message' => 'Не поддерживаемый формат импорта',
            ], 422);
        }

        if (isset($result['error'])) {
            return response()->json([
                'message' => $result['error'],
            ], 422);
        }

        if ($result->count() == 0) {
            return response()->json([
                'message' => 'В документе не обнаружено валидных данных, проверьте даты, время и адреса!',
            ], 422);
        }

        return response()->json([
            'purchases' => [...$result->toArray()],
        ]);
    }
}
