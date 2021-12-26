<?php

namespace App\Models;

use App\Traits\ObservantTrait;
use Illuminate\Database\Eloquent\Model;

class BatchStatus extends Model
{

    use ObservantTrait; // created, updated sync

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'batchStatus';

    protected $fillable = ['batchId', 'status', 'startTime', 'endTime', 'logFileName', 'delFlg', 'createdAt', 'createdBy',
        'updatedAt', 'updatedBy'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = FALSE;

}