<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        return view('pages.system.permission.index', [
            'title' => 'Permission Management'
        ]);
    }
}
