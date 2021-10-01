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
use App\Models\UserDevice;
use App\Models\BranchDetail;
use App\Models\UserReferral;
use App\Models\Categorizable;
use App\Models\DeviceSetting;
use App\Models\OperationHour;
use App\Models\UserSocialMedia;
use App\Observers\UserObserver;
use App\Models\UserNotification;
use App\Models\ApplicationHistory;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use App\Models\BranchVisitorHistory;
use Illuminate\Support\Facades\Hash;
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
        'name', 'email', 'mobile_no', 'type', 'publish', 'referral_code',
        'status', 'application_status', 'email_verified_at', 'password', 'remember_token',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $guard_name = 'web';

    // Constants
    const STATUS_ACTIVE                 =   'active';
    const STATUS_INACTIVE               =   'inactive';
    const LISTING_STATUS_PUBLISH        =   'publish';
    const LISTING_STATUS_DRAFT          =   'draft';
    const APPLICATION_STATUS_APPROVED   =   'approved';
    const APPLICATION_STATUS_PENDING    =   'pending';
    const APPLICATION_STATUS_REJECTED   =   'rejected';

    const USER_TYPE_ADMIN       =   'admin';
    const USER_TYPE_MERCHANT    =   'merchant';
    const USER_TYPE_MEMBER      =   'member';

    const STORE_BASE_DIRECTORY      =   '/users';
    const STORE_MEMBER_PATH         =   self::STORE_BASE_DIRECTORY . '/members';
    const STORE_MERCHANT_PATH       =   self::STORE_BASE_DIRECTORY . '/merchants';

    // Functions
    protected static function boot()
    {
        parent::boot();

        self::observe(UserObserver::class);
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    }

    public function revokeTokens(): void
    {
        foreach ($this->tokens as $token) {
            $token->revoke();
        }
    }

    public function checkUserFavouriteStatus(self $user = null)
    {
        if ($user) {

            return (bool) $this->favouriteBy->contains('id', $user->id);
        }

        return (bool) false;
    }

    // Relationships
    public function subBranches()
    {
        return $this->belongsToMany(self::class, Branch::class, 'main_branch_id', 'sub_branch_id', 'id', 'id');
    }

    public function mainBranch()
    {
        return $this->belongsToMany(self::class, Branch::class, 'sub_branch_id', 'main_branch_id', 'id', 'id');
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

    public function favourites() // user's favourite list
    {
        return $this->morphedByMany(self::class, 'favourable', Favourable::class);
    }

    public function favouriteBy() // branch favourite by user
    {
        return $this->morphToMany(self::class, 'favourable', Favourable::class);
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
        return $this->belongsToMany(DeviceSetting::class, UserDevice::class, 'user_id', 'device_id', 'id', 'id');
    }

    public function careers()
    {
        return $this->hasMany(Career::class, 'branch_id', 'id');
    }

    public function operationHours()
    {
        return $this->hasMany(OperationHour::class, 'branch_id', 'id');
    }

    public function visitorHistories()
    {
        return $this->hasMany(BranchVisitorHistory::class, 'branch_id', 'id');
    }

    public function branchVisitHistories()
    {
        return $this->hasMany(BranchVisitorHistory::class, 'visitor_id', 'id');
    }

    public function userNotifications()
    {
        return $this->hasMany(UserNotification::class, 'user_id', 'id');
    }

    public function merchantApplicationHistories()
    {
        return $this->hasMany(ApplicationHistory::class, 'user_id', 'id')->where('type', self::USER_TYPE_MERCHANT);
    }

    public function referrals()
    {
        return $this->belongsToMany(self::class, UserReferral::class, 'user_id', 'referral_id', 'id', 'id');
    }

    public function referredBy()
    {
        return $this->belongsToMany(self::class, UserReferral::class, 'referral_id', 'user_id', 'id', 'id');
    }

    public function userSocialMedia()
    {
        return $this->hasMany(UserSocialMedia::class, 'user_id', 'id');
    }

    // Scopes
    public function scopeAdmin($query)
    {
        return $query->where('type', self::USER_TYPE_ADMIN);
    }

    public function scopeMerchant($query)
    {
        return $query->where('type', self::USER_TYPE_MERCHANT);
    }

    public function scopeMainMerchant($query)
    {
        return $query->doesntHave('mainBranch');
    }

    public function scopeSubMerchant($query)
    {
        return $query->has('mainBranch');
    }

    public function scopeMember($query)
    {
        return $query->where('type', self::USER_TYPE_MEMBER);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    public function scopeApprovedApplication($query)
    {
        return $query->where('application_status', self::APPLICATION_STATUS_APPROVED);
    }

    public function scopePendingApplication($query)
    {
        return $query->where('application_status', self::APPLICATION_STATUS_PENDING);
    }

    public function scopeRejectedApplication($query)
    {
        return $query->where('application_status', self::APPLICATION_STATUS_REJECTED);
    }

    public function scopePublish($query)
    {
        return $query->where('listing_status', self::LISTING_STATUS_PUBLISH);
    }

    public function scopeDraft($query)
    {
        return $query->where('listing_status', self::LISTING_STATUS_DRAFT);
    }

    public function scopeValidMerchant($query)
    {
        return $query->merchant()->active()->approvedApplication();
    }

    public function scopeFilterMerchantByRating($query, $value = null)
    {
        $tbl_users      =   $this->getTable();
        $tbl_ratings    =   app(Rateable::class)->getTable();

        return $query->select($tbl_users . '.id', $tbl_users . '.name', $tbl_users . '.mobile_no', $tbl_users . '.status', $tbl_users . '.email', DB::raw('AVG(' . $tbl_ratings . '.scale) AS ratings'))
            ->join($tbl_ratings, $tbl_users . '.id', '=', $tbl_ratings . '.rateable_id')
            ->where($tbl_ratings . '.rateable_type', self::class)
            ->validMerchant()
            ->groupBy($tbl_users . '.id', $tbl_users . '.name', $tbl_users . '.mobile_no', $tbl_users . '.status', $tbl_users . '.email')
            ->when(empty($value), function ($query) {
                $query->having('ratings', '>=', 4);
            })
            ->when(!empty($value), function ($query) use ($value) {
                $query->having('ratings', 'like', "%{$value}%");
            })
            ->orderByDesc('ratings');
    }

    public function scopeSortMerchantByRating($query)
    {
        $tbl_users      =   $this->getTable();
        $tbl_ratings    =   app(Rateable::class)->getTable();

        return $query->select($tbl_users . '.id', $tbl_users . '.name', $tbl_users . '.mobile_no', $tbl_users . '.status', $tbl_users . '.email', DB::raw('AVG(' . $tbl_ratings . '.scale) AS ratings'))
            ->join($tbl_ratings, $tbl_users . '.id', '=', $tbl_ratings . '.rateable_id')
            ->where($tbl_ratings . '.rateable_type', self::class)
            ->validMerchant()
            ->groupBy($tbl_users . '.id', $tbl_users . '.name', $tbl_users . '.mobile_no', $tbl_users . '.status', $tbl_users . '.email')
            ->having('ratings', '>', 0)
            ->orderByDesc('ratings');
    }

    public function scopeFilterByLocationDistance($query, $latitude, $longitude)
    {
        return $query->whereHas('address', function ($query) use ($latitude, $longitude) {
            $query->filterByCoordinates($latitude, $longitude);
        });
    }

    public function scopeFilterByCategories($query, array $categories)
    {
        return $query->whereHas('categories', function ($query) use ($categories) {
            $query->whereIn('id', $categories);
        });
    }

    public function scopeSearchByAddress($query, $keyword)
    {
        return $query->whereHas('address', function ($query) use ($keyword) {
            $query->searchByAddress($keyword);
        });
    }

    public function scopeSearchByInput($query, $keyword)
    {
        return $query->where(function ($query) use ($keyword) {
            $query->orWhere('name', 'like', "%$keyword%")
                ->orWhereHas('mainBranch', function ($query) use ($keyword) {
                    $query->whereHas('categories', function ($query) use ($keyword) {
                        $query->where('name', 'like', "%$keyword%");
                    });
                })
                ->orWhere(function ($query) use ($keyword) {
                    $query->searchByAddress($keyword);
                });
        });
    }

    // Attributes
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = (!empty($value)) ? Hash::make(trim($value)) : $this->password;
    }

    public function setMobileNoAttribute($value)
    {
        if (!empty($value)) {
            $value = (new Misc())->phoneStoreFormat($value);
        }

        $this->attributes['mobile_no'] = $value;
    }

    public function getIsSuperAdminAttribute()
    {
        return $this->roles->first()->name == Role::ROLE_SUPER_ADMIN;
    }

    public function getIsAdminAttribute()
    {
        return $this->type == self::USER_TYPE_ADMIN;
    }

    public function getIsMerchantAttribute()
    {
        return $this->type == self::USER_TYPE_MERCHANT;
    }

    public function getIsMainBranchAttribute()
    {
        return !is_null($this->subBranches) && is_null($this->mainBranch);
    }

    public function getIsSubBranchAttribute()
    {
        return !$this->is_main_branch;
    }

    public function getIsMemberAttribute()
    {
        return $this->type == self::USER_TYPE_MEMBER;
    }

    public function getFormattedMobileNoAttribute()
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
        return number_format($this->ratings()->avg('scale'), 1);
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

    public function getMainBranchAttribute()
    {
        return $this->mainBranch()->first();
    }

    public function getStatusLabelAttribute()
    {
        $active_status  = $this->active_status_label;
        $listing_status = $this->listing_status_label;

        return $active_status . '<br/>' . $listing_status;
    }

    public function getListingStatusLabelAttribute()
    {
        $label = (new Status())->statusLabel($this->listing_status);

        return '<span class="' . $label['class'] . ' px-3">' . $label['text'] . '</span>';
    }

    public function getActiveStatusLabelAttribute()
    {
        $label = (new Status())->statusLabel($this->status);

        return '<span class="' . $label['class'] . ' px-3">' . $label['text'] . '</span>';
    }

    public function getApplicationStatusLabelAttribute()
    {
        $label = (new Status())->statusLabel($this->application_status);

        return '<span class="' . $label['class'] . ' px-3">' . $label['text'] . '</span>';
    }
}
