<?php

namespace App\Support\Services;

use Exception;
use App\Models\User;
use App\Helpers\Misc;
use App\Models\Media;
use App\Helpers\Geocoding;
use App\Helpers\FileManager;
use App\Models\BranchDetail;
use App\Models\OperationHour;
use App\Models\UserSocialMedia;
use App\Models\ApplicationHistory;
use App\Support\Services\BaseService;

class MerchantService extends BaseService
{
    public $from_verification;

    public function __construct()
    {
        parent::__construct(User::class);
    }

    public function storeMainMerchant()
    {
        $this->store()->setApplicationStatus(User::APPLICATION_STATUS_APPROVED);

        return $this;
    }

    public function storeBranch(User $main_branch)
    {
        $this->store()->setApplicationStatus(User::APPLICATION_STATUS_APPROVED);

        // sync to main branch
        $main_branch->subBranches()->syncWithoutDetaching([$this->model->id]);

        return $this;
    }

    public function store()
    {
        if ($this->request->has('name') && !empty($this->request->get('name'))) {
            $this->model->name = $this->request->get('name');
        }

        if ($this->request->has('phone') && !empty($this->request->get('phone'))) {
            $this->model->mobile_no = $this->request->get('phone');
        }

        if ($this->request->has('password') && !empty($this->request->get('password'))) {
            $this->model->password = $this->request->get('password');
        }

        if ($this->request->has('status') && !empty($this->request->get('status'))) {
            $this->model->status = $this->request->get('status', User::STATUS_ACTIVE);
        }

        if ($this->request->has('listing_status') && !empty($this->request->get('listing_status'))) {
            $this->model->listing_status = $this->request->get('listing_status', User::LISTING_STATUS_PUBLISH);
        }

        if ($this->request->has('email') && !empty($this->request->get('email'))) {
            $this->model->email = $this->request->get('email');
        }

        $this->model->type = User::USER_TYPE_MERCHANT;

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        $this->storeDetails()
            ->storeAddress()
            ->storeLogo()
            ->storeSsmCert()
            ->storeImage()
            ->storeOperatingHour()
            ->storeSocialMedia()
            ->assignCategory();

        return $this;
    }

    public function storeDetails()
    {
        $details = $this->model->branchDetail()->firstOr(function () {
            return new BranchDetail();
        });

        if ($this->request->has('reg_no') && !empty($this->request->get('reg_no'))) {
            $details->reg_no = $this->request->get('reg_no');
        }

        if ($this->request->has('pic_name') && !empty($this->request->get('pic_name'))) {
            $details->pic_name = $this->request->get('pic_name');
        }

        if ($this->request->has('pic_phone') && !empty($this->request->get('pic_phone'))) {
            $details->pic_contact = $this->request->get('pic_phone');
        }

        if ($this->request->has('pic_email') && !empty($this->request->get('pic_email'))) {
            $details->pic_email = $this->request->get('pic_email');
        }

        if ($this->request->has('description') && !empty($this->request->get('description'))) {
            $details->description = $this->request->get('description');
        }

        if ($this->request->has('services') && !empty($this->request->get('services'))) {
            $details->services = $this->request->get('services');
        }

        if ($this->request->has('career_desc') && !empty($this->request->get('career_desc'))) {
            $details->career_description = $this->request->get('career_desc');
        }

        if ($details->isDirty()) {
            $this->model->branchDetail()->save($details);
        }

        return $this;
    }

    public function storeLogo()
    {
        if ($this->request->hasFile('logo')) {

            $logo  =   $this->request->file('logo');

            $config = [
                'save_path'     => User::STORE_MERCHANT_PATH . '/' . $this->model->id,
                'type'          => Media::TYPE_LOGO,
                'filemime'      => (new FileManager())->getMimesType($logo->getClientOriginalExtension()),
                'filename'      => $logo->getClientOriginalName(),
                'extension'     => $logo->getClientOriginalExtension(),
                'filesize'      => $logo->getSize(),
            ];

            $media = $this->model->media()->logo()->firstOr(function () {
                return new Media();
            });

            $this->storeMedia($media, $config, $logo);
        }

        return $this;
    }

    public function storeSsmCert()
    {
        if ($this->request->hasFile('ssm_cert')) {

            $ssm_cert = $this->request->file('ssm_cert');

            $config = [
                'save_path'     => User::STORE_MERCHANT_PATH . '/' . $this->model->id,
                'type'          => Media::TYPE_SSM,
                'filemime'      => (new FileManager())->getMimesType($ssm_cert->getClientOriginalExtension()),
                'filename'      => $ssm_cert->getClientOriginalName(),
                'extension'     => $ssm_cert->getClientOriginalExtension(),
                'filesize'      => $ssm_cert->getSize(),
            ];

            $media = $this->model->media()->ssmCert()->firstOr(function () {
                return new Media();
            });

            $this->storeMedia($media, $config, $ssm_cert);
        }

        return $this;
    }

    public function storeImage()
    {
        if ($this->request->hasFile('files')) {

            $images = $this->request->file('files');

            throw_if(
                ($this->model->media()->imageAndThumbnail()->count() + count($images)) > Media::MAX_BRANCH_IMAGE_UPLOAD,
                new Exception(__('messages.files_reached_limit'))
            );

            foreach ($images as $image) {

                $config = [
                    'save_path'     => User::STORE_MERCHANT_PATH . '/' . $this->model->id,
                    'type'          => Media::TYPE_IMAGE,
                    'filemime'      => (new FileManager())->getMimesType($image->getClientOriginalExtension()),
                    'filename'      => $image->getClientOriginalName(),
                    'extension'     => $image->getClientOriginalExtension(),
                    'filesize'      => $image->getSize(),
                ];

                $this->storeMedia(new Media, $config, $image);
            }
        }

        if ($this->model->media()->image()->count() > 0) {

            $this->setThumbnail();
        }

        return $this;
    }

    public function setThumbnail()
    {
        if (!$this->model->wasRecentlyCreated && !$this->request->has('thumbnail')) {
            return $this;
        }

        $images = $this->model->media();

        if ($this->model->wasRecentlyCreated) { // get the first image as thumbnail if new User

            $new_thumbnail = (clone $images)->image()->first();
        } elseif ($this->request->has('thumbnail')) {

            $old_thumbnail = (clone $images)->thumbnail()->first();
            $new_thumbnail = (clone $images)->image()->where('id', $this->request->get('thumbnail'))->first();

            if ($old_thumbnail && $new_thumbnail) {
                $old_thumbnail->type = $new_thumbnail->type;
                $old_thumbnail->save();
            }
        }

        if ($new_thumbnail) {
            $new_thumbnail->type = Media::TYPE_THUMBNAIL;
            $new_thumbnail->save();
        }

        return $this;
    }

    public function setApplicationStatus(string $status = User::APPLICATION_STATUS_PENDING, string $remarks = NULL)
    {
        $this->model->application_status = $status;

        if ($this->model->isDirty()) {

            $this->model->save();
        }

        $this->storeApplicationHistory($status, $remarks);

        return $this;
    }

    public function assignCategory()
    {
        if ($this->model->is_main_branch && $this->request->has('category')) {

            $this->model->categories()->syncWithoutDetaching([$this->request->get('category')]);
        }

        return $this;
    }

    public function storeOperatingHour()
    {
        if ($this->request->has('operation')) {

            $operations = $this->request->get('operation');

            foreach ($operations as $day_of_week => $operation) {

                $operation_hour = $this->model->operationHours()
                    ->where('days_of_week', $day_of_week)
                    ->firstOr(function () {
                        return new OperationHour();
                    });

                $operation_hour->days_of_week   = $day_of_week;
                $operation_hour->day_off        = isset($operation['off_day']);
                $operation_hour->start          = $operation['start_from'];
                $operation_hour->end            = $operation['end_at'];

                if ($operation_hour->isDirty()) {
                    $this->model->operationHours()->save($operation_hour);
                }
            }
        }

        return $this;
    }

    public function storeVisitorHistory()
    {
        $user = $this->request->user('api');

        if ($user) {

            $this->model->visitorHistories()->attach($user->id);
        }

        return $this;
    }

    public function setLocationCoordinates()
    {
        $geocoder = (new Geocoding())->setStreetAddress($this->request->get('address_1'), $this->request->get('address_2'))
            ->setPostCode($this->request->get('postcode'))
            ->setCity($this->request->get('city'))
            ->setCountryState($this->request->get('country_state'))
            ->getCoordinatesForAddress();

        $address = $this->model->address()->first();
        $address->latitude  = $geocoder['lat'];
        $address->longitude = $geocoder['lng'];

        if ($address->isDirty()) {

            $this->model->address()->save($address);
        }

        return $this;
    }

    private function storeApplicationHistory(string $status, string $remarks = NULL)
    {
        $history = new ApplicationHistory();

        $history->status = $status;
        $history->remarks = $remarks;

        $this->model->merchantApplicationHistories()->save($history);

        return $this;
    }

    public function setReferral($column = 'id')
    {
        $referral_code = $this->request->get('referral_code');

        if (!empty($referral_code)) {
            $referral = User::admin()->when($column === 'id', function ($query) use ($referral_code) {
                $query->where('id', $referral_code);
            })->when($column !== 'id', function ($query) use ($referral_code) {
                $query->where('referral_code', $referral_code);
            })->firstOrFail();

            $this->model->referrals()->sync($referral->id, false);
        }

        return $this;
    }

    public function storeSocialMedia()
    {
        foreach ((new Misc())->getSocialMediaKeys() as $media_key => $media_text) {

            $social_media = $this->model->userSocialMedia()
                ->where('media_key', $media_key)
                ->firstOr(function () {
                    return new UserSocialMedia();
                });

            $social_media->media_key    = $media_key;
            $social_media->media_value  = ($media_key == UserSocialMedia::SOCIAL_MEDIA_KEY_WHATSAPP)
                ? (new Misc())->phoneStoreFormat($this->request->get($media_key))
                : $this->request->get($media_key);

            if ($social_media->isDirty()) {

                $this->model->userSocialMedia()->save($social_media);
            }
        }

        return $this;
    }

    public function storeMerchantProfile()
    {
        $this->model->name = $this->request->get('name');
        $this->model->mobile_no = $this->request->get('phone');
        $this->model->mobile_no = $this->request->get('phone');

        if ($this->request->has('password') && !empty($this->request->get('password'))) {
            $this->model->password = $this->request->get('password');
        }

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        return $this;
    }
}
