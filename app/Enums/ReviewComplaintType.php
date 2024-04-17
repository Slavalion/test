<?php

namespace App\Enums;

enum ReviewComplaintType: int
{
    case OBSCENE_LANGUAGE = 1;
    case DUMMY_REVIEW = 2;
    case SPAM = 3;
    case BAD_PRODUCT_IMAGE = 4;
    case NOT_ABOUT_PRODUCT = 5;

    public static function fromString(string $type): static
    {
        return match (true) {
            $type == strtolower(self::OBSCENE_LANGUAGE->name) => self::OBSCENE_LANGUAGE,
            $type == strtolower(self::DUMMY_REVIEW->name) => self::DUMMY_REVIEW,
            $type == strtolower(self::SPAM->name) => self::SPAM,
            $type == strtolower(self::BAD_PRODUCT_IMAGE->name) => self::BAD_PRODUCT_IMAGE,
            $type == strtolower(self::NOT_ABOUT_PRODUCT->name) => self::NOT_ABOUT_PRODUCT,
        };
    }

    public function toString(): string
    {
        return match ($this) {
            self::OBSCENE_LANGUAGE => strtolower(self::OBSCENE_LANGUAGE->name),
            self::DUMMY_REVIEW => strtolower(self::DUMMY_REVIEW->name),
            self::SPAM => strtolower(self::SPAM->name),
            self::BAD_PRODUCT_IMAGE => strtolower(self::BAD_PRODUCT_IMAGE->name),
            self::NOT_ABOUT_PRODUCT => strtolower(self::NOT_ABOUT_PRODUCT->name),
        };
    }
}
