<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ObservantTrait;

class TempCase extends Model
{
    use HasFactory, ObservantTrait;

    protected $table = 'temp_case';

    protected $primaryKey = 'temp_case_id';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'temp_case_id',
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

    public function car() {
        return $this->hasMany(Car::class, 'temp_case_id', 'temp_case_id');
    }

    public function mst_scrapper() {
        return $this->belongsTo(MstScrapper::class, 'scrapper_office_code', 'office_code');
    }

    public function mst_office_tr() {
        return $this->belongsTo(MstOffice::class, 'tr_office_code', 'office_code');
    }

    public function mst_office_sy() {
        return $this->belongsTo(MstOffice::class, 'sy_office_code', 'office_code');
    }

    public function temp_car() {
        return $this->hasMany(TempCar::class, 'temp_case_id', 'temp_case_id');
    }

    public function contract() {
        return $this->hasOne(Contract::class, 'temp_case_id', 'temp_case_id');
    }

    public function cases()
    {
        return $this->hasMany(Cases::class, 'temp_case_id', 'temp_case_id');
    }
}
