<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use App\Helpers\MenuHelper;

class RoleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name|max:255'
        ]);

        Role::create(['name' => strtolower($request->name)]);

        return redirect()->back()->with('success', 'Role berhasil ditambahkan');
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $menuGroups = MenuHelper::getAllMenuGroups();
        
        return view('pages.system.permission.edit', [
            'title' => 'Edit Role / Permission',
            'role' => $role,
            'menuGroups' => $menuGroups
        ]);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'string'
        ]);

        $role->update(['name' => strtolower($request->name)]);

        // Sync permissions
        $role->permissions()->delete(); // Remove old permissions
        
        if ($request->has('permissions')) {
            $permissionsData = array_map(function($path) use ($role) {
                return [
                    'role_id' => $role->id,
                    'menu_path' => $path,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }, $request->permissions);
            
            RolePermission::insert($permissionsData);
        }

        return redirect()->route('permission.index')->with('success', 'Role dan permission berhasil diperbarui');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if ($role->users()->count() > 0) {
            return redirect()->back()->with('error', 'Role tidak dapat dihapus karena masih digunakan oleh pengguna.');
        }

        $role->delete();

        return redirect()->back()->with('success', 'Role berhasil dihapus');
    }
}
