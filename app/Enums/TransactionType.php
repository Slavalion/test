<?php

namespace App\Enums;

enum TransactionType: int
{
    case TOP_UP = 1;
    case WRITE_OFF = -1;

    public function toTitle(): string
    {
        $result = match ($this) {
            TransactionType::TOP_UP => 'Пополнение',
            TransactionType::WRITE_OFF => 'Списание',
            default => 'Не определено',
        };

        return $result;
    }
}
