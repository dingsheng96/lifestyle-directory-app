<?php

namespace App\Support\Services;

use Exception;
use App\Models\Role;
use App\Models\User;
use App\Models\Media;
use App\Helpers\FileManager;
use App\Models\BranchDetail;
use App\Models\OperationHour;
use Illuminate\Support\Facades\Hash;
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
        $this->store()
            ->assignRole(Role::ROLE_MERCHANT_1)
            ->setApplicationStatus(User::APPLICATION_STATUS_APPROVED)
            ->assignCategory();

        return $this;
    }

    public function storeBranch(User $main_branch)
    {
        $this->store()
            ->assignRole(Role::ROLE_MERCHANT_2)
            ->setApplicationStatus(User::APPLICATION_STATUS_APPROVED);

        // sync to main branch
        $main_branch->subBranches()->syncWithoutDetaching([
            $this->model->id => [
                'status' => $this->request->get('branch_status', User::STATUS_BRANCH_PUBLISH)
            ]
        ]);

        return $this;
    }

    public function store()
    {
        $this->model->name      =   $this->request->get('name');
        $this->model->email     =   $this->request->get('email');
        $this->model->status    =   $this->request->get('status', User::STATUS_INACTIVE);
        $this->model->mobile_no =   $this->request->get('phone');
        $this->model->password  =   !empty($this->request->get('password'))
            ? Hash::make($this->request->get('password'))
            : $this->model->password;

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        $this->storeDetails();
        $this->storeAddress();
        $this->storeLogo();
        $this->storeSsmCert();
        $this->storeImage();
        $this->storeOperatingHour();

        return $this;
    }

    public function storeDetails()
    {
        $details = $this->model->branchDetail()->firstOr(function () {
            return new BranchDetail();
        });

        $details->reg_no        =   $this->request->get('reg_no');
        $details->pic_name      =   $this->request->get('pic_name');
        $details->pic_contact   =   $this->request->get('pic_phone');
        $details->pic_email     =   $this->request->get('pic_email');
        $details->description   =   $this->request->get('description');
        $details->services      =   $this->request->get('services');
        $details->website       =   $this->request->get('website');
        $details->facebook      =   $this->request->get('facebook');
        $details->whatsapp      =   $this->request->get('whatsapp');
        $details->instagram     =   $this->request->get('instagram');

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

    public function assignRole(string $role)
    {
        $this->model->syncRoles([$role]);

        return $this;
    }

    public function setApplicationStatus(string $status = User::APPLICATION_STATUS_PENDING)
    {
        $this->model->application_status = $status;

        if ($this->model->isDirty()) {

            $this->model->save();
        }

        return $this;
    }

    public function assignCategory()
    {
        if ($this->model->is_main_merchant) {

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
}
