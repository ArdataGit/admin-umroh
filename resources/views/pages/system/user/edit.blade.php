@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Edit User" :breadcrumbs="[
    ['label' => 'System', 'url' => '#'],
    ['label' => 'User', 'url' => route('user.index')],
    ['label' => 'Edit', 'url' => '#']
]" />

<div class="rounded-2xl border border-gray-200 bg-white p-6 md:p-8 dark:border-gray-800 dark:bg-white/[0.03] lg:p-10 max-w-4xl mx-auto">
    <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name -->
            <div class="col-span-1 md:col-span-2">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full rounded-lg border border-gray-200 bg-transparent px-4 py-2.5 text-sm outline-none transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white"
                    placeholder="Masukkan nama lengkap">
                @error('name') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full rounded-lg border border-gray-200 bg-transparent px-4 py-2.5 text-sm outline-none transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white"
                    placeholder="Masukkan email aktif">
                @error('email') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Password (Opsional)
                </label>
                <input type="password" name="password"
                    class="w-full rounded-lg border border-gray-200 bg-transparent px-4 py-2.5 text-sm outline-none transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white"
                    placeholder="Kosongkan jika tidak ingin mengubah password">
                <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter.</p>
                @error('password') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Role -->
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Permission (Role) <span class="text-red-500">*</span>
                </label>
                <select name="role_id" required class="w-full rounded-lg border border-gray-200 bg-transparent px-4 py-2.5 text-sm outline-none transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white dark:bg-gray-900">
                    <option value="" disabled>Pilih Permission</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ ucwords($role->name) }}</option>
                    @endforeach
                </select>
                @error('role_id') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Status -->
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status" required class="w-full rounded-lg border border-gray-200 bg-transparent px-4 py-2.5 text-sm outline-none transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white dark:bg-gray-900">
                    <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Avatar -->
            <div class="col-span-1 md:col-span-2">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Foto Avatar / Profile
                </label>
                
                @if($user->avatar)
                    <div class="mb-3 flex items-center gap-4">
                        <img src="{{ asset('avatars/' . $user->avatar) }}" alt="Current Avatar" class="h-16 w-16 rounded-full object-cover border border-gray-200 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Avatar saat ini</span>
                    </div>
                @endif
                
                <input type="file" name="avatar" accept="image/jpeg,image/png,image/jpg"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/30 dark:file:text-blue-400 dark:text-gray-400">
                <p class="mt-1 text-xs text-gray-500">Pilih file baru jika ingin mengganti. Maksimal 2MB. Format: JPG, JPEG, PNG.</p>
                @error('avatar') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mt-8 flex items-center gap-3">
            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-colors">
                Update User
            </button>
            <a href="{{ route('user.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
