<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->latest()->get();
        return view('pages.system.permission.index', [
            'title' => 'Permission Management',
            'roles' => $roles
        ]);
    }
}
