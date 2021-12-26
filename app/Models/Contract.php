<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ObservantTrait;
use Carbon\Carbon;
class Contract extends Model
{
    use HasFactory, ObservantTrait;

    protected $table = 'contract';

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

    public function temp_case() {
        return $this->belongsTo(TempCase::class, 'temp_case_id', 'temp_case_id');
    }

    public function getContractDateFormatAttribute()
    {   
        return $this->contract_date ? Carbon::parse($this->contract_date)->format('Y/m/d') : null;
    }
}
