<?php

namespace App\Helpers;

use App\Models\UserSocialMedia;

class Misc
{
    public function phoneStoreFormat(string $phone = null)
    {
        if (!is_null($phone)) {
            $phone = preg_replace('/[^0-9]/', '', $phone);

            if (substr($phone, 0, 2) != '60') {

                $phone = '60' . ltrim($phone, '0');
            }
        }

        return $phone;
    }

    public function addTagsToPhone(string $phone = null)
    {
        if (is_null($phone)) {

            return $phone;
        }

        $format = chunk_split($phone, 4, ' ');

        return '+' . rtrim($format, ' ');
    }

    public function getSocialMediaKeys()
    {
        return [
            UserSocialMedia::SOCIAL_MEDIA_KEY_WHATSAPP    => __('labels.whatsapp'),
            UserSocialMedia::SOCIAL_MEDIA_KEY_FACEBOOK    => __('labels.facebook'),
            UserSocialMedia::SOCIAL_MEDIA_KEY_WEBSITE     => __('labels.website'),
            UserSocialMedia::SOCIAL_MEDIA_KEY_INSTAGRAM   => __('labels.instagram'),
            UserSocialMedia::SOCIAL_MEDIA_KEY_TWITTER     => __('labels.twitter'),
            UserSocialMedia::SOCIAL_MEDIA_KEY_LINKEDIN    => __('labels.linkedin'),
            UserSocialMedia::SOCIAL_MEDIA_KEY_SHOPEE      => __('labels.shopee'),
            UserSocialMedia::SOCIAL_MEDIA_KEY_LAZADA      => __('labels.lazada'),
            UserSocialMedia::SOCIAL_MEDIA_KEY_YOUTUBE     => __('labels.youtube'),
            UserSocialMedia::SOCIAL_MEDIA_KEY_ECATALOGUE  => __('labels.e_catalogue')
        ];
    }
}
