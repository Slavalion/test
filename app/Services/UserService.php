<?php

namespace App\Services;

use App\Enums\UserPriceType;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Collection;

class UserService
{
    protected Collection $prices;

    public function __construct(public User $user)
    {
        $this->prices = $this->getPrices();
    }

    public function getPrices(): array|Collection
    {
        $settingKeys = array_map(function ($priceType) {
            return 'price.'.$priceType->value;
        }, UserPriceType::cases());

        $defaultPrices = Setting::whereIn('key', $settingKeys)->get();

        $userPrices = $this->user->prices;

        $prices = $defaultPrices->mapWithKeys(function ($el) {
            $priceType = str_replace('price.', '', $el->key);

            return [
                $priceType => $el->value,
            ];
        });

        $prices = $prices->toBase()->merge($userPrices->mapWithKeys(function ($el) {
            $priceType = $el->type->value;

            return [
                $priceType => $el->value,
            ];
        }));

        return $prices;
    }

    public function getPrice(UserPriceType $userPriceType): int
    {
        if (! $this->prices->has($userPriceType->value)) {
            throw new \Exception('User price type not found', 1);
        }

        return $this->prices->get($userPriceType->value);
    }
}
