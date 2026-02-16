<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('pages.profile', [
            'user' => auth()->user(),
            'title' => 'Profile'
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            $request->validate([
                'avatar' => 'image|mimes:jpeg,png,jpg,gif',
            ]);

            $avatarName = time().'.'.$request->avatar->extension();
            $request->avatar->move(public_path('avatars'), $avatarName);

            // Delete old avatar if exists and not default
            if ($user->avatar && file_exists(public_path('avatars/'.$user->avatar))) {
                unlink(public_path('avatars/'.$user->avatar));
            }

            $user->avatar = $avatarName;
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
