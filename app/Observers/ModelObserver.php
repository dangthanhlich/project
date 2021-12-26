<?php

namespace App\Observers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ModelObserver
{

    public function creating(Model $model) {
        $model->created_at = Carbon::now();
        $model->created_by = auth()->user()->id;
        $model->updated_at = Carbon::now();
        $model->updated_by = auth()->user()->id;
        $model->deleted_by = NULL;
        $model->deleted_at = NULL;
    }

    public function updating(Model $model) {
        $model->updated_at = Carbon::now();
        $model->updated_by = auth()->user()->id;
    }
}
