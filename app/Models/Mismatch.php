<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ObservantTrait;

class Mismatch extends Model
{
    use HasFactory, ObservantTrait;

    protected $table = 'mismatch';

    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = FALSE;

    public function case() {
        return $this->belongsTo(Cases::class, 'case_id', 'case_id');
    }
}
