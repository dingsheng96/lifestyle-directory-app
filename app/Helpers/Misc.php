<?php

namespace App\Helpers;

class Misc
{
    public function phoneStoreFormat(string $phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (substr($phone, 0, 2) != '60') {

            $phone = '60' . ltrim($phone, '0');
        }

        return $phone;
    }

    public function addTagsToPhone(string $phone)
    {
        $format = chunk_split($phone, 4, ' ');

        return '+' . rtrim($format, ' ');
    }
}
