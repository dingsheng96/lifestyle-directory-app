<?php

namespace App\Models;

use App\Models\Role;
use App\Helpers\Misc;
use App\Models\Media;
use App\Helpers\Status;
use App\Models\Address;
use App\Models\Service;
use App\Models\Rateable;
use App\Models\Favourable;
use App\Models\BranchDetail;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes, HasRoles;

    const STATUS_ACTIVE     =   'active';
    const STATUS_INACTIVE   =   'inactive';
    const STORE_PATH        =   '/users';

    protected $table = 'users';

    protected $fillable = [
        'name', 'mobile_no', 'email', 'password',
        'remember_token', 'status', 'email_verified_at'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationships
    public function subBranches()
    {
        return $this->hasMany(Branch::class, 'main_branch_id', 'id');
    }

    public function mainBranch()
    {
        return $this->belongsTo(Branch::class, 'sub_branch_id', 'id');
    }

    public function branchDetail()
    {
        return $this->hasOne(BranchDetail::class, 'user_id', 'id');
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'sourceable');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'sourceable');
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
        return $this->morphToMany(self::class, 'rateable', Rateable::class)->withPivot('scale')->withTimestamps();
    }

    public function ratedBy()
    {
        return $this->morphedByMany(self::class, 'rateable', Rateable::class)->withPivot('scale')->withTimestamps();
    }

    // Functions
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
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

    // Attributes
    public function setMobileNoAttribute($value)
    {
        if (!empty($value)) {
            $value = (new Misc())->phoneStoreFormat($value);
        }

        $this->attributes['mobile_no'] = $value;
    }

    public function getFullAddressAttribute()
    {
        if ($this->address) {
            $full_address  =    $this->address->address_1 . ', ';
            $full_address  .=   $this->address->address_2 . ', ';
            $full_address  .=   $this->address->postcode . ', ';
            $full_address  .=   $this->address->city->name . ', ';
            $full_address  .=   $this->address->city->country_state_name . ', ';
            $full_address  .=   'Malaysia';
        }

        return $full_address ?? null;
    }

    public function getStatusLabelAttribute()
    {
        $label = (new Status())->statusLabel($this->status);

        return '<span class="' . $label['class'] . ' px-3">' . $label['text'] . '</span>';
    }

    public function getLogoAttribute()
    {
        return $this->media()->logo()->first();
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
        if (empty($this->phone)) {
            return null;
        }

        return (new Misc())->addTagsToPhone($this->phone);
    }

    public function getCategoryAttribute()
    {
        return $this->categories->first();
    }

    public function getRatingAttribute(): int
    {
        return round($this->ratings()->avg('scale'));
    }
}
