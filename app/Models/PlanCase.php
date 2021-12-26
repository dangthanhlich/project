<?php

namespace App\Models;

use App\Traits\ObservantTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanCase extends Model
{
    use HasFactory, ObservantTrait;

    protected $table = 'plan_case';

    protected $dateFormat = 'Y/m/d';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'del_flg',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'deleted_by',
        'deleted_at',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = FALSE;

    /**
     * format date for receive_plan_date
     */
    public function getReceivePlanDateAttribute($value) {
        if (empty($value)) {
            return '';
        } else {
            return Carbon::parse($value)->format($this->dateFormat);
        }
    }
}
