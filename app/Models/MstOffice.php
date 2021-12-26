<?php

namespace App\Models;

use App\Traits\ObservantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstOffice extends Model
{
    use HasFactory, ObservantTrait;

    protected $table = 'mst_office';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
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

    public function mst_scrapper_tr() {
        return $this->hasMany(MstScrapper::class, 'office_code', 'tr_office_code');
    }

    public function mst_scrapper_sy() {
        return $this->hasMany(MstScrapper::class, 'office_code', 'sy_office_code');
    }
    public function case() {
        return $this->hasMany(Cases::class, 'tr_office_code', 'office_code');
    }
}
