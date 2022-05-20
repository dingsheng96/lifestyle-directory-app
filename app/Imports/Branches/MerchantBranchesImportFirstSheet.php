<?php

namespace App\Imports\Branches;

use App\Models\City;
use App\Models\User;
use App\Helpers\Misc;
use App\Rules\PhoneFormat;
use Maatwebsite\Excel\Row;
use App\Models\BranchDetail;
use App\Models\CountryState;
use App\Rules\UniqueMerchant;
use App\Models\UserSocialMedia;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Events\Registered;
use App\Support\Services\MerchantService;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MerchantBranchesImportFirstSheet implements OnEachRow, WithValidation, SkipsEmptyRows, WithHeadingRow
{
    use Importable;

    protected $merchant;

    public function __construct(User $merchant)
    {
        $this->merchant = $merchant->load([
            'categories',
            'operationHours' => function ($query) {
                $query->orderBy('days_of_week');
            }
        ]);
    }

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row = $row->toArray();

        $city = City::with('countryState')
            ->where('name', $row['city'])
            ->first();

        if (!empty($this->merchant->operationHours)) {
            foreach ($this->merchant->operationHours as $operation_hour) {
                $operation_hours[$operation_hour->days_of_week] = [
                    'start_from' => $operation_hour->start,
                    'end_at' => $operation_hour->end
                ];

                if ($operation_hour->day_off) {
                    $operation_hours[$operation_hour->days_of_week] += [
                        'off_day' => 'on'
                    ];
                }
            }

            $row = array_merge($row, ['operation' => $operation_hours]);
        }

        $data = array_merge($row, [
            'name' => (array_key_exists('company_name', $row) && !empty($row['company_name'])) ? $row['company_name'] : $this->merchant->name,
            'email' => $row['login_email'],
            'phone' => (new Misc())->phoneStoreFormat($row['mobile_no']),
            'status' => User::STATUS_ACTIVE,
            'listing_status' => User::LISTING_STATUS_DRAFT,
            'reg_no' => $row['ssm_no'],
            'pic_name' => $row['pic_name'],
            'pic_email' => $row['pic_email'],
            'pic_phone' => (new Misc())->phoneStoreFormat($row['pic_contact_no']),
            'description' => (array_key_exists('description', $row) && !empty($row['description'])) ? $row['description'] : $this->merchant->branchDetail->description,
            'services' => (array_key_exists('services', $row) && !empty($row['services'])) ? $row['services'] : $this->merchant->branchDetail->services,
            'career_desc' => (array_key_exists('career_description', $row) && !empty($row['career_description'])) ? $row['career_description'] : $this->merchant->branchDetail->career_description,
            'address_1' => $row['address_1'],
            'address_2' => $row['address_2'],
            'postcode' => $row['postcode'],
            'city' => $city->id,
            'country_state' => $city->countryState->id
        ]);

        $branch = (new MerchantService())
            ->setRequest(request()->replace($data))
            ->storeBranch($this->merchant)
            ->getModel();

        if ($branch) {
            //send email
            event(new Registered($branch));
        }
    }

    public function rules(): array
    {
        $rules = [
            '*.company_name' => [
                'nullable', 'min:3', 'max:255'
            ],
            '*.login_email' => [
                'required', 'email',
                'distinct', new UniqueMerchant('email')
            ],
            '*.mobile_no' => [
                'required', new PhoneFormat
            ],
            '*.ssm_no' => [
                'required', Rule::unique(BranchDetail::class, 'reg_no')
                    ->whereNull('deleted_at')
            ],
            '*.pic_name' => [
                'required'
            ],
            '*.pic_contact_no' => [
                'required', new PhoneFormat
            ],
            '*.pic_email' => [
                'required', 'email'
            ],
            '*.address_1' => [
                'required', 'min:3', 'max:255'
            ],
            '*.address_2' => [
                'nullable'
            ],
            '*.postcode' => [
                'required', 'digits:5'
            ],
            '*.state' => [
                'required', 'exists:' . CountryState::class . ',name'
            ],
            '*.city' => [
                'required', 'exists:' . City::class . ',name'
            ],
            '*.description' => [
                'nullable', 'string'
            ],
            '*.services' => [
                'nullable', 'string'
            ],
            '*.career_description' => [
                'nullable', 'string'
            ],
        ];

        $social_media_rules = [];

        foreach ((new Misc())->getSocialMediaKeys() as $media_key => $media_text) {

            if ($media_key == UserSocialMedia::SOCIAL_MEDIA_KEY_WHATSAPP) {
                $social_media_rules['*.' . $media_key] = ['nullable', new PhoneFormat];
                continue;
            }

            $social_media_rules['*.' . $media_key] = ['nullable', 'url'];
        }

        return array_merge($rules, $social_media_rules);
    }
}
