<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Gate;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Check permission
     * @param array $roles
     * @return mixed
     */
    public function checkPermission($roles) {
        $result = false;
        foreach ($roles as $role) {
            if (Gate::check($role) || (empty(auth()->user()) && $role == 'is_dismantling')) {
                $result = true;
            }
        }
        if (!$result) {
            abort(404);
        }
    }

    /**
     * handle pagination
     */
    public function pagination($query, Request $request) {
        $limit = Config::get('Constant.Values.Common.pagination');
        // check limit from url
        if (!empty($request->query('limit'))) {
            $limit = $request->query('limit');
        }

        return $query->paginate($limit)
                    ->appends(['limit' => $limit])
                    ->withQueryString();
    }
}
