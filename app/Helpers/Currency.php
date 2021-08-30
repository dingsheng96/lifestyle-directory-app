<?php

namespace App\Helpers;

class Currency
{
    public static function instance()
    {
        return new self();
    }

    public function convertPriceFromIntToFloat(int $price): float
    {
        return (float) $price / 100;
    }

    public function convertPriceFromFloatToInt(float $price): int
    {
        $split = explode('.', (string) $price);

        $result = $split[0] . '00';

        if (count($split) > 1) {
            $numerator = rtrim($split[1], '0');
            $result += $numerator . '0';
        }

        return (int) $result;
    }
}
