<?php

namespace App\Models;

use App\Models\Role;
use App\Helpers\Misc;
use App\Models\Media;
use App\Models\Branch;
use App\Models\Career;
use App\Helpers\Status;
use App\Models\Address;
use App\Models\Category;
use App\Models\Rateable;
use App\Models\Favourable;
use App\Models\BranchDetail;
use App\Models\Categorizable;
use App\Models\DeviceSetting;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes, HasRoles, HasApiTokens;

    protected $table = 'users';

    protected $fillable = [
        'name', 'mobile_no', 'email', 'password', 'remember_token',
        'status', 'application_status', 'email_verified_at'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Constants
    const STATUS_ACTIVE             =   'active';
    const STATUS_INACTIVE           =   'inactive';
    const STATUS_BRANCH_PUBLISH     =   'publish';
    const STATUS_BRANCH_DRAFT       =   'draft';

    const APPLICATION_STATUS_APPROVED   =   'approved';
    const APPLICATION_STATUS_PENDING    =   'pending';
    const APPLICATION_STATUS_REJECTED   =   'rejected';

    const STORE_BASE_DIRECTORY      =   '/users';
    const STORE_MEMBER_PATH         =   self::STORE_BASE_DIRECTORY . '/members';
    const STORE_MERCHANT_PATH       =   self::STORE_BASE_DIRECTORY . '/merchants';

    // Relationships
    public function subBranches()
    {
        return $this->belongsToMany(self::class, Branch::class, 'main_branch_id', 'sub_branch_id', 'id', 'id')->withPivot(['status']);
    }

    public function mainBranch()
    {
        return $this->belongsToMany(self::class, Branch::class, 'sub_branch_id', 'main_branch_id', 'id', 'id')->withPivot(['status']);
    }

    public function branchDetail()
    {
        return $this->hasOne(BranchDetail::class, 'branch_id', 'id');
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function favourites()
    {
        return $this->morphedByMany(self::class, 'favourable', Favourable::class);
    }

    public function favouriteBy()
    {
        return $this->morphedByMany(self::class, 'favourable', Favourable::class);
    }

    public function ratings()
    {
        return $this->morphToMany(self::class, 'rateable', Rateable::class)->withPivot(['scale', 'review'])->withTimestamps();
    }

    public function raters()
    {
        return $this->morphedByMany(self::class, 'rateable', Rateable::class)->withPivot(['scale', 'review'])->withTimestamps();
    }

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorizable', Categorizable::class);
    }

    public function deviceSettings()
    {
        return $this->hasMany(DeviceSetting::class, 'user_id', 'id');
    }

    public function careers()
    {
        return $this->hasMany(Career::class, 'branch_id', 'id');
    }

    // Functions
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    }

    public function revokeTokens()
    {
        foreach ($this->tokens as $token) {
            $token->revoke();
        }

        return;
    }

    // Scopes
    public function scopeAdmin($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('name', Role::ROLE_SUPER_ADMIN);
        });
    }

    public function scopeMainMerchant($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('name', Role::ROLE_MERCHANT_1);
        });
    }

    public function scopeSubMerchant($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('name', Role::ROLE_MERCHANT_2);
        });
    }

    public function scopeMerchant($query)
    {
        return $query->mainMerchant()
            ->orWhere(function ($query) {
                $query->subMerchant();
            });
    }

    public function scopeMember($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('name', Role::ROLE_MEMBER);
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeApprovedApplication($query)
    {
        return $query->where('application_status', self::APPLICATION_STATUS_APPROVED);
    }

    public function scopeFilterMerchantByRating($query, $value)
    {
        $tbl_user   =   $this->getTable();
        $tbl_rating =   app(Rateable::class)->getTable();

        return $query->select($tbl_user . '.id', DB::raw('AVG(' . $tbl_rating . '.scale) AS ratings'))
            ->join($tbl_rating, $tbl_user . '.id', '=', $tbl_rating . '.rateable_id')
            ->where($tbl_rating . '.rateable_type', self::class)
            ->groupBy($tbl_user . '.id')
            ->having('ratings', 'like', "{$value}%");
    }

    public function scopeSortMerchantByRating($query)
    {
        $tbl_users      =   $this->getTable();
        $tbl_ratings    =   app(Rateable::class)->getTable();

        return $query->select($tbl_users . '.id', $tbl_users . '.name', $tbl_users . '.status', DB::raw('AVG(' . $tbl_ratings . '.scale) AS ratings'))
            ->join($tbl_ratings, $tbl_users . '.id', '=', $tbl_ratings . '.rateable_id')
            ->where($tbl_ratings . '.rateable_type', self::class)
            ->active()
            ->mainMerchant()
            ->orWhere(function ($query) {
                $query->subMerchant();
            })
            ->groupBy($tbl_users . '.id', $tbl_users . '.name', $tbl_users . '.status')
            ->having('ratings', '>', 0)
            ->orderByDesc('ratings');
    }

    public function scopeFilterByLocationDistance($query, $latitude, $longitude)
    {
        return $query->whereHas('address', function ($query) use ($latitude, $longitude) {
            $query->filterByCoordinates($latitude, $longitude);
        });
    }

    public function scopeFilterByCategories($query, $categories)
    {
        return $query->whereHas('categories', function ($query) use ($categories) {
            $query->whereIn('id', $categories);
        });
    }

    // Attributes
    public function setMobileNoAttribute($value)
    {
        if (!empty($value)) {
            $value = (new Misc())->phoneStoreFormat($value);
        }

        $this->attributes['mobile_no'] = $value;
    }

    public function getStatusLabelAttribute()
    {
        $label = (new Status())->statusLabel($this->status);

        return '<span class="' . $label['class'] . ' px-3">' . $label['text'] . '</span>';
    }

    public function getRoleNameAttribute()
    {
        return $this->roles()->first()->name;
    }

    public function getIsAdminAttribute()
    {
        return $this->role_name == Role::ROLE_SUPER_ADMIN;
    }

    public function getIsMainMerchantAttribute()
    {
        return $this->role_name == Role::ROLE_MERCHANT_1;
    }

    public function getIsSubMerchantAttribute()
    {
        return $this->role_name == Role::ROLE_MERCHANT_2;
    }

    public function getIsMemberAttribute()
    {
        return $this->role_name == Role::ROLE_MEMBER;
    }

    public function getFolderNameAttribute()
    {
        $folders = [
            Role::ROLE_SUPER_ADMIN  => 'admin',
            Role::ROLE_MERCHANT_1   => 'merchant',
            Role::ROLE_MERCHANT_2   => 'merchant',
            Role::ROLE_MEMBER       => 'member'
        ];

        return $folders[$this->role_name];
    }

    public function getFormattedPhoneNumberAttribute()
    {
        if (empty($this->mobile_no)) {
            return null;
        }

        return (new Misc())->addTagsToPhone($this->mobile_no);
    }

    public function getCategoryAttribute()
    {
        return $this->categories->first();
    }

    public function getRatingAttribute()
    {
        return number_format($this->ratings->avg('scale'), 1);
    }

    public function getProfileImageAttribute()
    {
        return collect($this->media)->firstWhere('type', Media::TYPE_PROFILE_IMAGE);
    }

    public function getCoverPhotoAttribute()
    {
        return collect($this->media)->firstWhere('type', Media::TYPE_COVER_PHOTO);
    }

    public function getImageAttribute()
    {
        return collect($this->media)->where('type', Media::TYPE_IMAGE)->all();
    }

    public function getLogoAttribute()
    {
        return collect($this->media)->firstWhere('type', Media::TYPE_LOGO);
    }

    public function getThumbnailAttribute()
    {
        return collect($this->media)->where('type', Media::TYPE_THUMBNAIL)->first();
    }

    public function getSsmCertAttribute()
    {
        return collect($this->media)->where('type', Media::TYPE_SSM)->first();
    }

    public function getBranchStatusLabelAttribute()
    {
        $active_status  = $this->status_label;
        $publish_status = (new Status())->statusLabel($this->main_branch->pivot->status);

        return $active_status . '<br><span class="' . $publish_status['class'] . ' px-3">' . $publish_status['text'] . '</span>';
    }

    public function getMainBranchAttribute()
    {
        return $this->mainBranch()->first();
    }
}
