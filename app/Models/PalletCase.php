<?php

namespace App\Models;

use App\Traits\ObservantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PalletCase extends Model
{
    use HasFactory, ObservantTrait;

    protected $table = 'pallet_case';

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

    public function pallet() {
        return $this->belongsTo(Pallet::class, 'pallet_id', 'pallet_id');
    }

    public function case() {
        return $this->hasOne(Cases::class, 'case_id', 'case_id');
    }
}
