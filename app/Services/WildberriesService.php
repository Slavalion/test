<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WildberriesService
{
    public array $product;

    public function __construct(public string $productCode)
    {
    }

    public function getProduct()
    {
        $response = Http::withHeaders([
            'Accept' => '*/*',
        ])->get('https://card.wb.ru/cards/detail?appType=1&curr=rub&dest=-1257786&nm='.$this->productCode);

        $responseData = $response->json();

        if (! isset($responseData['data']['products'][0])) {
            return null;
        }

        $this->product = $responseData['data']['products'][0];

        return $this->product;
    }

    public function getReviews()
    {
        $this->product = $this->getProduct();
        $feedbackUrlDigit = $this->feedbackUrlDigit($this->product['root']);

        $response = Http::withHeaders([
            'Accept' => '*/*',
        ])->get('https://feedbacks'.$feedbackUrlDigit.'.wb.ru/feedbacks/v1/'.$this->product['root']);

        $responseData = $response->json();

        return $responseData['feedbacks'];
    }

    public function feedbackUrlDigit($itm)
    {
        $t = $itm;

        $arr[] = [0, 0, 0, 0, 0, 0, 0, 0];

        for ($i = 0; $i < 8; $i++) {
            $arr[$i] = $t % 256;
            $t = floor($t / 256);
        }

        $n = 0;

        for ($i = 0; $i < 8; $i++) {
            $n ^= $arr[$i];

            for ($j = 0; $j < 8; $j++) {
                if ((1 & $n) > 0) {
                    $n = ($n >> 1) ^ 40961;
                } else {
                    $n >>= 1;
                }
            }
        }

        return $n % 100 >= 50 ? '2' : '1';
    }
}
