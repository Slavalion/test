<?php

namespace App\Enums;

use App\Models\ActionCart;
use App\Models\ActionCartGroup;
use App\Models\ActionSearch;
use App\Models\ActionSearchGroup;
use App\Models\PickUp;
use App\Models\Purchase;
use App\Models\PurchaseGroup;
use App\Models\ReviewComplaint;
use App\Models\ReviewComplaintGroup;
use App\Models\ReviewReaction;
use App\Models\ReviewReactionGroup;

enum TransactionTarget: string
{
    case ACTION_CART = ActionCart::class;
    case ACTION_CART_GROUP = ActionCartGroup::class;
    case ACTION_SEARCH = ActionSearch::class;
    case ACTION_SEARCH_GROUP = ActionSearchGroup::class;
    case BALANCE_MANUALLY = 'balance_manually';
    case LIVECARGO = 'livecargo';
    case PICK_UP = PickUp::class;
    case PURCHASE = PurchaseGroup::class;
    case PURCHASE_ITEM = Purchase::class;
    case RETURN_PICK_UP = 'return_pickup';
    case RETURN_PURCHASE = 'return_purchase';
    case RETURN_REVIEW = 'return_review';
    case REVIEW = 'review';
    case REVIEW_COMPLAINT = ReviewComplaint::class;
    case REVIEW_COMPLAINT_GROUP = ReviewComplaintGroup::class;
    case REVIEW_REACTION = ReviewReaction::class;
    case REVIEW_REACTION_GROUP = ReviewReactionGroup::class;
    case TINKOFF = 'tinkoff';
    case OLD_DEFAULT_VALUE = '';

    public function toTitle(): string
    {
        $result = match ($this) {
            TransactionTarget::ACTION_CART => '',
            TransactionTarget::ACTION_CART_GROUP => 'Действия: добавление в корзину',
            TransactionTarget::ACTION_SEARCH => '',
            TransactionTarget::ACTION_SEARCH_GROUP => 'Действия: поиск по запросам',
            TransactionTarget::BALANCE_MANUALLY => 'Пополение баланса (поддержка)',
            TransactionTarget::LIVECARGO => 'Забор товара',
            TransactionTarget::PICK_UP => 'Забор',
            TransactionTarget::PURCHASE => 'Группа выкупов',
            TransactionTarget::PURCHASE_ITEM => 'Выкуп',
            TransactionTarget::RETURN_PICK_UP => 'Возврат за забор',
            TransactionTarget::RETURN_PURCHASE => 'Возврат за выкуп',
            TransactionTarget::RETURN_REVIEW => 'Возврат за отзыв',
            TransactionTarget::REVIEW => 'Отзыв',
            TransactionTarget::REVIEW_COMPLAINT => '',
            TransactionTarget::REVIEW_COMPLAINT_GROUP => 'Жалобы на отзыв',
            TransactionTarget::REVIEW_REACTION => 'Реакция на отзыв',
            TransactionTarget::REVIEW_REACTION_GROUP => 'Реакции на отзыв',
            TransactionTarget::TINKOFF => 'Пополнение баланса',
            TransactionTarget::OLD_DEFAULT_VALUE => '(Пополнение баланса)',
            default => 'Не определено',
        };

        return $result;
    }
}
