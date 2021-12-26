<?php

namespace App\Models;

use App\Traits\ObservantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempCar extends Model
{
    use HasFactory, ObservantTrait;

    protected $table = 'temp_car';

    protected $primaryKey = 'temp_car_id';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'temp_car_id',
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

    public function temp_case() {
        return $this->belongsTo(TempCase::class, 'temp_case_id', 'temp_case_id');
    }
}
