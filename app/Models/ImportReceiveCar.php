<?php

namespace App\Models;

use App\Traits\ObservantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportReceiveCar extends Model
{
    use HasFactory, ObservantTrait;

    protected $table = 'import_receive_car';
}
