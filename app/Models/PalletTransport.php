<?php

namespace App\Models;

use App\Traits\ObservantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PalletTransport extends Model
{
    use HasFactory, ObservantTrait;

    protected $table = 'pallet_transport';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = FALSE;

    public function pallets() {
        return $this->hasMany(Pallet::class, 'pallet_transport_id', 'pallet_transport_id');
    }
}
