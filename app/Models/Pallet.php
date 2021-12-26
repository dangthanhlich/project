<?php

namespace App\Models;

use App\Traits\ObservantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pallet extends Model
{
    use HasFactory, ObservantTrait;

    protected $table = 'pallet';

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

    public function palletTransport() {
        return $this->belongsTo(PalletTransport::class, 'pallet_transport_id', 'pallet_transport_id');
    }

    public function palletCases() {
        return $this->hasMany(PalletCase::class, 'pallet_id', 'pallet_id');
    }

    public function cases() {
        return $this->hasManyThrough(
            Cases::class,
            PalletCase::class,
            'pallet_id',
            'case_id',
            'pallet_id',
            'case_id'
        );
    }

    public function palletMstOffice() {
        return $this->belongsTo(MstOffice::class, 'sy_office_code', 'office_code');
    }
}
