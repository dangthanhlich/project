<?php

namespace App\Models;

use App\Traits\ObservantTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class MstPrice extends Model
{
    use HasFactory, ObservantTrait;

    protected $table = 'mst_price';

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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sy_office_code',
        'price_type',
        'region_code',
        'unit_price',
        'effective_start_date',
        'effective_end_date',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = FALSE;

    protected $dateFormat = 'Y/m/d';

    public function getEffectiveStartDateAttribute($value) {
        if (empty($value)) {
            return '';
        } else {
            return Carbon::parse($value)->format($this->dateFormat);
        }
    }

    public function getEffectiveEndDateAttribute($value) {
        if (empty($value)) {
            return '';
        } else {
            return Carbon::parse($value)->format($this->dateFormat);
        }
    }

    public function mst_office() {
        return $this->belongsTo(MstOffice::class, 'sy_office_code', 'office_code');
    }
}
