<?php

namespace App\Http\Controllers\Admin;

use App\Permission;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\Request;

class PermissionController
{
    public function indexx()
    {
        return PermissionResource::collection(Permission::all());
    }
}
