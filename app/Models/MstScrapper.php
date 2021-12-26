<?php

namespace App\Models;

use App\Traits\ObservantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstScrapper extends Model
{
    use HasFactory, ObservantTrait;

    protected $table = 'mst_scrapper';

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

    public function mst_office_tr() {
        return $this->belongsTo(MstOffice::class, 'tr_office_code', 'office_code');
    }

    public function mst_office_sy() {
        return $this->belongsTo(MstOffice::class, 'sy_office_code', 'office_code');
    }

    public function mst_user() {
        return $this->belongsTo(MstUser::class, 'tr_office_code', 'office_code');
    }

    public function mst_price() {
        return $this->hasMany(MstPrice::class, 'office_code', 'sy_office_code');
    }

    public function case() {
        return $this->hasMany(Cases::class, 'scrapper_office_code', 'office_code');
    }

    public function temp_case() {
        return $this->hasMany(TempCase::class, 'scrapper_office_code', 'office_code');
    }

}
