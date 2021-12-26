<?php

namespace App\Models;

use App\Traits\ObservantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory, ObservantTrait;

    protected $table = 'car';

    protected $primaryKey = 'car_id';

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

    public function case() {
        return $this->belongsTo(Cases::class, 'case_id', 'case_id');
    }

    public function getIsDisplayMachanicalTypeAttribute()
    {
        return in_array($this->mechanical_type, ['001', '002', '003', '006', '007', '008', '009']);
    }

    public function getCarNoSubAttribute()
    {
        if (strlen($this->car_no) <= 4) {
            return $this->car_no;
        }

        return substr($this->car_no, strlen($this->car_no) - 4, 4);
    }
}
