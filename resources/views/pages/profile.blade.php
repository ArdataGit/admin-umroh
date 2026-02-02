@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Edit Profile" :breadcrumbs="[['label' => 'Profile', 'url' => '#']]" />

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
        <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">Edit Profile</h3>

        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-gray-800 dark:text-green-400" role="alert">
                <span class="font-medium">Success!</span> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="space-y-6">
                <!-- Avatar -->
                <div x-data="{
                        photoName: null,
                        photoPreview: null,
                        updatePreview() {
                            const file = this.$refs.photo.files[0];
                            if (!file) return;
                            this.photoName = file.name;
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.photoPreview = e.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    }">
                   <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Photo Profile
                    </label>
                    <div class="flex items-center gap-4">
                        <div class="h-16 w-16 overflow-hidden rounded-full border border-gray-200">
                            <!-- Current Profile Photo -->
                            <div x-show="!photoPreview">
                                @if($user->avatar)
                                    <img src="{{ asset('avatars/'.$user->avatar) }}" alt="User Avatar" class="h-full w-full object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" alt="User Avatar" class="h-full w-full object-cover">
                                @endif
                            </div>
                            <!-- New Profile Photo Preview -->
                            <div x-show="photoPreview" style="display: none;">
                                <img :src="photoPreview" alt="User Avatar" class="h-full w-full object-cover">
                            </div>
                        </div>
                        <input type="file" x-ref="photo" name="avatar" @change="updatePreview()" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:text-gray-400 dark:file:bg-gray-800 dark:file:text-gray-300">
                    </div>
                </div>

                <!-- Name -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Name
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:placeholder:text-gray-500" />
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Email Address
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:placeholder:text-gray-500" />
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            New Password (Optional)
                        </label>
                        <input type="password" name="password"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:placeholder:text-gray-500" />
                        @error('password')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Confirm Password
                        </label>
                        <input type="password" name="password_confirmation"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-500 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:placeholder:text-gray-500" />
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex justify-end pt-4">
                    <button type="submit"
                        class="rounded-lg bg-brand-500 px-6 py-3 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 focus:outline-none focus:ring-4 focus:ring-brand-500/20">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
