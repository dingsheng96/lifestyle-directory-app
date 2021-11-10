<?php

namespace App\Support\Services;

use App\Models\User;
use App\Models\Media;
use App\Models\UserDevice;
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

    public function linkDevice()
    {
        $device = DeviceSetting::where('device_id', $this->request->get('device_id'))->first();

        throw_if(!$device, new \Exception('Device ID not found.'));

        $device->users()->sync([$this->model->id]);

        (new DeviceService())->setModel($device)->updateDeviceLastActivationDate(); // update last activation date

        return $this;
    }

    public function resetPassword(string $password)
    {
        $this->model->password = $password;

        $this->model->save();

        return $this;
    }

    public function storeWishlist()
    {
        $merchant = User::validMerchant()->publish()->where('id', $this->request->get('merchant_id'))->firstOrFail();

        $this->model->favourites()->toggle([$merchant->id]);

        return $this;
    }

    public function rateMerchant()
    {
        $merchant = User::validMerchant()
            ->publish()
            ->where('id', $this->request->get('merchant_id'))
            ->firstOrFail();

        throw_if(
            $this->model->raters()->where('id', $merchant->id)->exists(),
            new \Exception('User already reviewed the same merchant.')
        );

        $this->model->raters()
            ->syncWithoutDetaching([
                $merchant->id => [
                    'scale'     => $this->request->get('scale'),
                    'review'    => $this->request->get('review')
                ]
            ]);

        return $this;
    }
}
