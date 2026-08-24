<?php

namespace App\Support;

class DecimalNormalizer
{
    public static function normalize(mixed $value): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        $normalized = str_replace(['R$', ' ', "\u{00A0}"], '', trim($value));

        if (! preg_match('/^-?[0-9.,]+$/', $normalized)) {
            return $value;
        }

        $lastComma = strrpos($normalized, ',');
        $lastDot = strrpos($normalized, '.');

        if ($lastComma === false && $lastDot === false) {
            return $normalized;
        }

        $decimalPosition = max($lastComma === false ? -1 : $lastComma, $lastDot === false ? -1 : $lastDot);
        $integer = preg_replace('/[.,]/', '', substr($normalized, 0, $decimalPosition));
        $fraction = preg_replace('/[.,]/', '', substr($normalized, $decimalPosition + 1));

        return $fraction === '' ? $integer : $integer.'.'.$fraction;
    }
}
