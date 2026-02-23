@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <x-common.page-breadcrumb pageTitle="Edit Role / Permission" :breadcrumbs="[['label' => 'System', 'url' => '#'], ['label' => 'Permission', 'url' => route('permission.index')], ['label' => 'Edit', 'url' => '#']]" />
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="mb-6 rounded-lg bg-green-50 p-4 border border-green-200 dark:bg-green-500/10 dark:border-green-500/20">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 text-green-600 dark:text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-medium text-green-800 dark:text-green-400">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="p-6 md:p-8">
            <form action="{{ route('permission.update', $role->id) }}" method="POST" class="space-y-6 max-w-2xl">
                @csrf
                @method('PUT')

                <!-- Role Name -->
                <div>
                    <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Nama Role / Permission <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" required 
                           class="w-full rounded-lg border border-gray-200 bg-transparent px-4 py-2.5 text-sm outline-none transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white" 
                           placeholder="Bisa menggunakan huruf kecil, cth: finance"
                           @if($role->name === 'super-admin') readonly disabled @endif>
                    @if($role->name === 'super-admin')
                        <input type="hidden" name="name" value="super-admin">
                        <p class="mt-1 text-xs text-blue-600">Nama role super-admin tidak dapat diubah.</p>
                    @endif
                    @error('name') 
                        <span class="mt-1 block text-xs text-red-500">{{ $message }}</span> 
                    @enderror
                </div>

                <hr class="border-gray-200 dark:border-gray-800 my-6">

                <!-- Hak Akses Sidebar Menus -->
                <div>
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Hak Akses Menu Sidebar</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Atur menu apa saja yang dapat dilihat dan diakses oleh Role ini di sidebar kiri.</p>
                    </div>

                    @php
                        $rolePermissions = $role->permissions->pluck('menu_path')->toArray();
                        // For super-admin, we might just force everything checked or ignore
                    @endphp

                    <div class="space-y-6">
                        @foreach($menuGroups as $group)
                            <div class="rounded-xl border border-gray-100 bg-gray-50 p-5 dark:border-gray-800 dark:bg-gray-800/50">
                                <h4 class="mb-4 text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white">{{ $group['title'] }}</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                    @foreach($group['items'] as $item)
                                        @if(isset($item['subItems']))
                                            <div class="col-span-1 md:col-span-2 mt-2">
                                                <div class="text-sm font-semibold text-blue-600 dark:text-blue-400 mb-2 flex items-center gap-2">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                                    {{ $item['name'] }}
                                                </div>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 pl-4">
                                                    @foreach($item['subItems'] as $subItem)
                                                        @php 
                                                            // Standardize path (remove trailing/leading slashes just in case, but keep leading for value)
                                                            $pathValue = '/' . ltrim($subItem['path'], '/'); 
                                                            $isChecked = in_array($pathValue, $rolePermissions) || $role->name === 'super-admin';
                                                        @endphp
                                                        <label class="flex items-center justify-between cursor-pointer rounded-lg p-2 hover:bg-white dark:hover:bg-gray-800 transition-colors border border-transparent hover:border-gray-200 dark:hover:border-gray-700">
                                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $subItem['name'] }}</span>
                                                            <div class="relative">
                                                                <input type="checkbox" name="permissions[]" value="{{ $pathValue }}" class="sr-only peer" {{ $isChecked ? 'checked' : '' }} @if($role->name === 'super-admin') disabled @endif>
                                                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-600/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                                            </div>
                                                        </label>
                                                        @if($role->name === 'super-admin' && $isChecked)
                                                            <!-- Required hidden field since disabled checkbox isn't submitted -->
                                                            <input type="hidden" name="permissions[]" value="{{ $pathValue }}">
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            @php 
                                                $pathValue = '/' . ltrim($item['path'], '/'); 
                                                $isChecked = in_array($pathValue, $rolePermissions) || $role->name === 'super-admin';
                                            @endphp
                                            <label class="flex items-center justify-between cursor-pointer rounded-lg p-2 hover:bg-white dark:hover:bg-gray-800 transition-colors border border-transparent hover:border-gray-200 dark:hover:border-gray-700">
                                                <div class="flex items-center gap-2">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $item['name'] }}</span>
                                                </div>
                                                <div class="relative">
                                                    <input type="checkbox" name="permissions[]" value="{{ $pathValue }}" class="sr-only peer" {{ $isChecked ? 'checked' : '' }} @if($role->name === 'super-admin') disabled @endif>
                                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-600/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                                </div>
                                            </label>
                                            @if($role->name === 'super-admin' && $isChecked)
                                                <!-- Required hidden field since disabled checkbox isn't submitted -->
                                                <input type="hidden" name="permissions[]" value="{{ $pathValue }}">
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-200 dark:border-gray-800 mt-8">
                    <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                        Pastikan penamaan role jelas karena akan muncul di menu pilihan untuk pembuatan akun User.
                    </p>
                    <div class="flex items-center gap-3">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('permission.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
