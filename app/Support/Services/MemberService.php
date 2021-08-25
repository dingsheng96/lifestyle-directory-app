<?php

namespace App\Support\Services;

use App\Models\Role;
use App\Models\User;
use App\Models\Media;
use App\Helpers\FileManager;
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
        $this->model->password  =   !empty($this->request->get('password'))
            ? Hash::make($this->request->get('password'))
            : $this->model->password;

        if (!$this->model->exists) { // new User

            $this->model->email_verified_at =   now();
            $this->model->assignRole(Role::ROLE_MEMBER);
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
                'save_path'     => User::STORE_PROFILE_IMAGE_PATH,
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
                'save_path'     => User::STORE_COVER_PHOTO_PATH,
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
}
