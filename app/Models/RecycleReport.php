<?php

namespace App\Models;

use App\Traits\ObservantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecycleReport extends Model
{
    use HasFactory, ObservantTrait;

    protected $table = 'recycle_report';

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

    public function mst_office() {
        return $this->belongsTo(MstOffice::class, 'rp_office_code', 'office_code');
    }
}
