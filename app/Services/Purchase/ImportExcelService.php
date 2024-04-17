<?php

namespace App\Services\Purchase;

use App\Models\Product;
use App\Models\WbPickpoints;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ImportExcelService
{
    public function importSingleProduct(Collection $rows)
    {
        $rows->shift()->all();

        if (trim($rows[0][1]) == '') {
            return [
                'error' => 'No product code',
            ];
        }

        $productCode = $rows[0][0];
        $purchaseAtDate = Date::excelToDateTimeObject($rows[0][4]);
        $purchaseAtTimeStart = Date::excelToDateTimeObject($rows[0][5])->format('H:i');
        $purchaseAtTimeEnd = Date::excelToDateTimeObject($rows[0][6])->format('H:i');

        if (Carbon::parse($purchaseAtDate)->lt(now()->today())) {
            return [
                'error' => 'Нельзя делать выкупы вчерашним числом',
            ];
        }

        $addresses = collect();
        $searchPhrases = collect();

        foreach ($rows as $row) {
            if (trim($row[1]) != '' && $row[2] > 0) {
                $searchPhrases->push([
                    'keywords' => $row[1],
                    'counter' => $row[2],
                ]);
            }

            if (trim($row[3]) != '') {
                if (WbPickpoints::where('address', preg_replace('/\s+/', ' ', trim($row[3])))->exists()) {
                    $addresses->push($row[3]);
                }
            }
        }

        $addresses = collect($addresses->shuffle()->all());

        $startTime = explode(':', $purchaseAtTimeStart);
        $endTime = explode(':', $purchaseAtTimeEnd);

        $startTimeHours = $startTime[0];
        $startTimeMinutes = $startTime[1];

        $endTimeHours = $endTime[0];
        $endTimeMinutes = $endTime[1];

        $purchaseAt = Carbon::parse($purchaseAtDate);
        $purchases = collect();

        $addressPointer = 0;
        $productArray = $this->getWbProduct($productCode);

        foreach ($searchPhrases as $phrase) {
            for ($i = 0; $i < $phrase['counter']; $i++) {
                $purchaseAt->setTimeFromTimeString('00:00');

                $randomHour = rand($startTimeHours, $endTimeHours);

                if ($randomHour == $endTimeHours && $endTimeMinutes == 0) {
                    $randomHour = $randomHour - 1;
                }

                $purchaseAt->addHours($randomHour);

                if ($randomHour == $startTimeHours) {
                    $randomMinutes = rand($startTimeMinutes, 59);
                } elseif ($randomHour == $endTimeHours) {
                    $randomMinutes = rand(0, $endTimeMinutes);
                } else {
                    $randomMinutes = rand(0, 59);
                }

                $purchaseAt->addMinutes($randomMinutes);

                $purchases->push([
                    ...$productArray,
                    'keywords' => $phrase['keywords'],
                    'address' => $addresses[$addressPointer],
                    'purchase_at' => $purchaseAt->toDateTimeLocalString(),
                ]);

                $addressPointer++;

                if ($addressPointer == $addresses->count()) {
                    $addressPointer = 0;
                }
            }
        }

        $sortedPurchases = $purchases->sortBy(function ($obj, $key) {
            return $obj['purchase_at'];
        });

        return $sortedPurchases;
    }

    public function importMultipleProduct(Collection $rows, Collection $addresses)
    {
        $rows->shift()->all();

        $sections = [];
        $sectionIndex = -1;

        foreach ($rows as $row) {
            if ($row[0] != '') {
                $sectionIndex++;

                $sections[$sectionIndex] = [
                    'product_code' => $row[0],
                    'purchase_at_date' => Date::excelToDateTimeObject($row[3]),
                    'purchase_at_time_start' => Date::excelToDateTimeObject($row[4])->format('H:i'),
                    'purchase_at_time_end' => Date::excelToDateTimeObject($row[5])->format('H:i'),
                ];
            }

            if ($row[1] != '') {
                $sections[$sectionIndex]['search_phrases'][] = [
                    'keywords' => $row[1],
                    'counter' => $row[2],
                ];
            }
        }

        $preparedAddresses = collect();

        foreach ($addresses->unique() as $address) {
            if (trim($address[0]) != '' && WbPickpoints::where('address', preg_replace('/\s+/', ' ', trim($address[0])))->exists()) {
                $preparedAddresses->push($address[0]);
            }
        }

        if ($preparedAddresses->count() == 0) {
            return [
                'error' => 'Не найдены адреса',
            ];
        }

        $preparedAddresses = collect($preparedAddresses->shuffle()->all());

        $purchases = collect();
        $addressPointer = 0;

        foreach ($sections as $section) {
            $startTime = explode(':', $section['purchase_at_time_start']);
            $endTime = explode(':', $section['purchase_at_time_end']);

            $startTimeHours = $startTime[0];
            $startTimeMinutes = $startTime[1];

            $endTimeHours = $endTime[0];
            $endTimeMinutes = $endTime[1];

            $purchaseAt = Carbon::parse($section['purchase_at_date']);

            if ($purchaseAt->lt(now()->today())) {
                continue;
            }

            $productArray = $this->getWbProduct($section['product_code']);

            if (! $productArray) {
                continue;
            }

            foreach ($section['search_phrases'] as $phrase) {
                for ($i = 0; $i < $phrase['counter']; $i++) {
                    $purchaseAt->setTimeFromTimeString('00:00');

                    $randomHour = rand($startTimeHours, $endTimeHours);

                    if ($randomHour == $endTimeHours && $endTimeMinutes == 0) {
                        $randomHour = $randomHour - 1;
                    }

                    $purchaseAt->addHours($randomHour);

                    if ($randomHour == $startTimeHours) {
                        $randomMinutes = rand($startTimeMinutes, 59);
                    } elseif ($randomHour == $endTimeHours) {
                        $randomMinutes = rand(0, $endTimeMinutes);
                    } else {
                        $randomMinutes = rand(0, 59);
                    }

                    $purchaseAt->addMinutes($randomMinutes);

                    $purchases->push([
                        ...$productArray,
                        'keywords' => $phrase['keywords'],
                        'address' => $preparedAddresses[$addressPointer],
                        'purchase_at' => $purchaseAt->toDateTimeLocalString(),
                    ]);

                    $addressPointer++;

                    if ($addressPointer == $preparedAddresses->count()) {
                        $addressPointer = 0;
                    }
                }
            }
        }

        $sortedPurchases = $purchases->sortBy(function ($obj, $key) {
            return $obj['purchase_at'];
        });

        return $sortedPurchases;
    }

    private function getWbProduct($productCode): array|bool
    {
        $product = Product::where('remote_id', $productCode)->first();

        $response = Http::get('https://card.wb.ru/cards/detail?appType=1&curr=rub&dest=-1257786&nm='.$productCode);
        $responseData = $response->json();

        if (! isset($responseData['data']['products'][0])) {
            return false;
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
            'sizes' => array_map(function ($element) {
                return [
                    'id' => $element['optionId'],
                    'name' => $element['name'],
                ];
            }, $productData['sizes']),
            'errors' => [],
        ];

        $productArray['price'] = $productArray['price'] / 100;

        return $productArray;
    }
}
