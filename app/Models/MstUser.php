<?php

namespace App\Models;

use App\Libs\EncryptUtil;
use App\Traits\ObservantTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MstUser extends Authenticatable
{
    use HasFactory, ObservantTrait;

    protected $table = 'mst_user';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = FALSE;

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
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    protected $dateTimeFormat = 'Y/m/d H:i';

    /**
     * Decrypt login_id value before response
     */
    public function getLoginIdAttribute($value) {
        return EncryptUtil::decryptAes256($value);
    }

    /**
     * Decrypt user_name value before response
     */
    public function getUserNameAttribute($value) {
        return EncryptUtil::decryptAes256($value);
    }

    public function getLastLoginAttribute($value) {
        if (empty($value)) {
            return '';
        } else {
            return Carbon::parse($value)->format($this->dateTimeFormat);
        }
    }

    /**
     * Decrypt email value before response
     */
    public function getEmailAttribute($value) {
        return EncryptUtil::decryptAes256($value);
    }

    public function mst_scrapper() {
        return $this->hasMany(MstScrapper::class, 'office_code', 'tr_office_code');
    }

    public function mst_office() {
        return $this->belongsTo(MstOffice::class, 'office_code', 'office_code');
    }

}
