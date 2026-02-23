<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->latest()->get();
        return view('pages.system.user.index', [
            'title' => 'User Management',
            'users' => $users
        ]);
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('pages.system.user.create', [
            'title' => 'Tambah User Baru',
            'roles' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:active,inactive',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $avatarName = null;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('avatars'), $filename);
            $avatarName = $filename;
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'status' => $validated['status'],
            'avatar' => $avatarName,
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::orderBy('name')->get();
        return view('pages.system.user.edit', [
            'title' => 'Edit User',
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:active,inactive',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('avatars'), $filename);
            
            if ($user->avatar && file_exists(public_path('avatars/' . $user->avatar))) {
                unlink(public_path('avatars/' . $user->avatar));
            }
            
            $user->avatar = $filename;
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role_id = $validated['role_id'];
        $user->status = $validated['status'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->avatar && file_exists(public_path('avatars/' . $user->avatar))) {
            unlink(public_path('avatars/' . $user->avatar));
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus');
    }
}
