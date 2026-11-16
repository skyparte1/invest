<?php

namespace App\Support;

class CurrencyFormatter
{
    public static function brl(float $value): string
    {
        return (round($value, 2) < 0 ? '-R$ ' : 'R$ ').number_format(abs($value), 2, ',', '.');
    }

    public static function percentage(float $value, int $decimals = 2): string
    {
        return number_format($value, $decimals, ',', '.').'%';
    }
}
