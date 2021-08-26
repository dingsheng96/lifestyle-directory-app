<?php

namespace App\Models;

use App\Models\User;
use App\Helpers\Status;
use App\Helpers\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Career extends Model
{
    use SoftDeletes;

    protected $table = 'careers';

    protected $fillable = [
        'branch_id', 'position', 'description', 'min_salary',
        'max_salary', 'show_salary', 'status'
    ];

    protected $casts = [
        'show_salary' => 'boolean'
    ];

    // Constants
    const STATUS_PUBLISH    = 'publish';
    const STATUS_DRAFT      = 'draft';

    // Relationships
    public function branch()
    {
        return $this->belongsTo(User::class, 'branch_id', 'id');
    }

    // Attributes
    public function setMinSalaryAttribute($value)
    {
        $this->attributes['min_salary'] = Currency::instance()->convertPriceFromFloatToInt($value);
    }

    public function setMaxSalaryAttribute($value)
    {
        $this->attributes['max_salary'] = Currency::instance()->convertPriceFromFloatToInt($value);
    }

    public function getFormattedMinSalaryAttribute()
    {
        return Currency::instance()->convertPriceFromIntToFloat($this->min_salary);
    }

    public function getFormattedMaxSalaryAttribute()
    {
        if (!empty($this->max_salary)) {

            return Currency::instance()->convertPriceFromIntToFloat($this->max_salary);
        }

        return null;
    }

    public function getStatusLabelAttribute()
    {
        $label = (new Status())->statusLabel($this->status);

        return '<span class="' . $label['class'] . ' px-3">' . $label['text'] . '</span>';
    }

    public function getSalaryRangeAttribute()
    {
        $min_salary = $this->formatted_min_salary;
        $max_salary = $this->formatted_max_salary;

        if (empty($max_salary)) {
            return number_format($min_salary, 2);
        }

        return number_format($min_salary, 2) . ' - ' . number_format($max_salary, 2);
    }
}
