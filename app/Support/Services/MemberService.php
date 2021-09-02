<?php

namespace App\Support\Services;

use App\Models\User;
use App\Models\Media;
use App\Helpers\FileManager;
use App\Models\DeviceSetting;
use Illuminate\Support\Facades\Hash;
use App\Support\Services\BaseService;

class MemberService extends BaseService
{
    public function __construct()
    {
        parent::__construct(User::class);
    }

    public function store()
    {
        $this->model->name      =   $this->request->get('name');
        $this->model->mobile_no =   $this->request->get('phone');
        $this->model->email     =   $this->request->get('email');
        $this->model->status    =   $this->request->get('status', User::STATUS_ACTIVE);
        $this->model->type      =   User::USER_TYPE_MEMBER;
        $this->model->password  =   $this->request->get('password');

        $this->model->application_status = User::APPLICATION_STATUS_APPROVED;

        if (!$this->model->exists) { // new User

            $this->model->email_verified_at = now();
        }

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        $this->storeImage();

        return $this;
    }

    public function storeImage()
    {
        if ($this->request->hasFile('profile_image')) {

            $profile_image  =   $this->request->file('profile_image');

            $config = [
                'save_path'     => User::STORE_MEMBER_PATH . '/' . $this->model->id,
                'type'          => Media::TYPE_PROFILE_IMAGE,
                'filemime'      => (new FileManager())->getMimesType($profile_image->getClientOriginalExtension()),
                'filename'      => $profile_image->getClientOriginalName(),
                'extension'     => $profile_image->getClientOriginalExtension(),
                'filesize'      => $profile_image->getSize(),
            ];

            $media = $this->model->media()->profileImage()->firstOr(function () {
                return new Media();
            });

            $this->storeMedia($media, $config, $profile_image);
        }

        if ($this->request->hasFile('cover_photo')) {

            $cover_photo = $this->request->file('cover_photo');

            $config = [
                'save_path'     => User::STORE_MEMBER_PATH . '/' . $this->model->id,
                'type'          => Media::TYPE_COVER_PHOTO,
                'filemime'      => (new FileManager())->getMimesType($cover_photo->getClientOriginalExtension()),
                'filename'      => $cover_photo->getClientOriginalName(),
                'extension'     => $cover_photo->getClientOriginalExtension(),
                'filesize'      => $cover_photo->getSize(),
            ];

            $media = $this->model->media()->coverPhoto()->firstOr(function () {
                return new Media();
            });

            $this->storeMedia($media, $config, $cover_photo);
        };

        return $this;
    }

    public function setUserType(string $type = User::USER_TYPE_MEMBER)
    {
        $this->model->type = $type;

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        return $this;
    }

    public function storeGuest()
    {
        $this->model->name                  =   'Guest_' . time();
        $this->model->email                 =   'guest' . time() . '@guest.com';
        $this->model->status                =   User::STATUS_ACTIVE;
        $this->model->type                  =   User::USER_TYPE_GUEST;
        $this->model->password              =   Hash::make('password' . time());
        $this->model->application_status    =   User::APPLICATION_STATUS_APPROVED;

        if (!$this->model->exists) { // new User

            $this->model->email_verified_at = now();
        }

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        return $this;
    }

    public function storeDevice()
    {
        $device = $this->model->deviceSettings()
            ->where('device_id', $this->request->get('device_id'))
            ->firstOr(function () {
                return new DeviceSetting();
            });

        $device->device_id                  =   $this->request->get('device_id');
        $device->device_os                  =   $this->request->get('device_os');
        $device->push_messaging_token       =   $this->request->get('push_messaging_token');
        $device->enable_push_messaging      =   $this->request->get('enable_push_messaging');
        $device->enable_notification_sound  =   $this->request->get('enable_notification_sound');

        if ($device->isDirty()) {

            $this->model->deviceSettings()->save($device);
        }

        return $this;
    }

    public function changeActiveDevice()
    {
        if ($active_device = $this->model->deviceSettings()->active()->first()) {

            $active_device->status  =   DeviceSetting::STATUS_INACTIVE;

            $this->model->deviceSettings()->save($active_device);
        }

        if ($target_device = DeviceSetting::where('device_id', $this->request->get('device_id'))->first()) {

            $target_device->user_id =   $this->model->id;
            $target_device->status  =   DeviceSetting::STATUS_ACTIVE;

            $this->model->deviceSettings()->save($target_device);
        }

        return $this;
    }

    public function resetPassword()
    {
        $this->model->password = $this->request->get('new_password');

        $this->model->save();

        return $this;
    }

    public function storeWishlist()
    {
        $merchant = User::validMerchant()->where('id', $this->request->get('merchant_id'))->firstOrFail();

        $this->model->favourites()->toggle([$merchant->id]);

        return $this;
    }

    public function storeMerchantRating()
    {
        $merchant = User::validMerchant()->where('id', $this->request->get('merchant_id'))->firstOrFail();

        $this->model->raters()->attach([
            $merchant->id => [
                'scale'     => $this->request->get('scale'),
                'review'    => $this->request->get('review')
            ]
        ]);

        return $this;
    }
}
