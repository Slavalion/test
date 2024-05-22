<?php

namespace App\Enums;

enum LivecargoDeliveryPickedUpEnum: int
{
    case PICKED_UP = 1;
    case PENDING = 0;
    case FAILURE = -1;

    public function toTitle(): string
    {
        $result = match ($this) {
            LivecargoDeliveryPickedUpEnum::PICKED_UP => 'Забран',
            LivecargoDeliveryPickedUpEnum::PENDING => 'Ожидает обработки',
            LivecargoDeliveryPickedUpEnum::FAILURE => 'Не забран',
            default => 'Не определено',
        };

        return $result;
    }
}
