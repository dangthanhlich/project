<?php

namespace App\Models;

use App\Traits\ObservantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiffReceiveReport extends Model
{
    use HasFactory, ObservantTrait;

    protected $table = 'diff_receive_report';
}
