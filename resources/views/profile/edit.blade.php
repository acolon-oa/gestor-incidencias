@extends('layouts.app')

@section('title', 'User Settings')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="mb-10">
        <h1 class="text-3xl font-black tracking-tight">User Settings</h1>
        <p class="opacity-60">Manage your profile information and account security.</p>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success mb-8 rounded-xl border-none shadow-sm font-bold">
            <x-heroicon-o-check-circle class="w-6 h-6" />
            <span>Profile updated successfully.</span>
        </div>
    @endif

    <div class="space-y-12">
        <!-- Profile Information -->
        <div class="bg-base-100 border border-base-300 rounded-3xl shadow-sm p-8">
            <h2 class="text-xs font-black opacity-40 uppercase tracking-[0.2em] mb-8">Profile Information</h2>
            
            <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                @csrf
                @method('patch')

                <div class="form-control">
                    <label class="label pt-0"><span class="label-text font-bold opacity-70">Full Name</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                        class="input input-bordered w-full rounded-xl focus:ring-4 focus:ring-primary/10 transition-all font-semibold" required>
                </div>

                <div class="form-control">
                    <label class="label pt-0"><span class="label-text font-bold opacity-70">Email Address</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                        class="input input-bordered w-full rounded-xl focus:ring-4 focus:ring-primary/10 transition-all font-semibold" required>
                </div>

                <div class="flex items-center gap-4 pt-4 border-t border-base-200">
                    <button type="submit" class="btn btn-primary px-10 rounded-xl font-black shadow-lg shadow-primary/20">Save Profile</button>
                    @if (session('status') === 'profile-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm opacity-50 font-bold italic">Saved.</p>
                    @endif
                </div>
            </form>
        </div>

        <!-- Update Password -->
        <div class="bg-base-100 border border-base-300 rounded-3xl shadow-sm p-8">
            <h2 class="text-xs font-black opacity-40 uppercase tracking-[0.2em] mb-8">Update Password</h2>
            
            <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                @method('put')

                <div class="form-control">
                    <label class="label pt-0"><span class="label-text font-bold opacity-70">Current Password</span></label>
                    <input type="password" name="current_password" 
                        class="input input-bordered w-full rounded-xl focus:ring-4 focus:ring-primary/10 transition-all" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label pt-0"><span class="label-text font-bold opacity-70">New Password</span></label>
                        <input type="password" name="password" 
                            class="input input-bordered w-full rounded-xl focus:ring-4 focus:ring-primary/10 transition-all font-semibold" required>
                    </div>

                    <div class="form-control">
                        <label class="label pt-0"><span class="label-text font-bold opacity-70">Confirm New Password</span></label>
                        <input type="password" name="password_confirmation" 
                            class="input input-bordered w-full rounded-xl focus:ring-4 focus:ring-primary/10 transition-all font-semibold" required>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4 border-t border-base-200">
                    <button type="submit" class="btn btn-primary px-10 rounded-xl font-black shadow-lg shadow-primary/20">Update Password</button>
                    @if (session('status') === 'password-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm opacity-50 font-bold italic">Updated.</p>
                    @endif
                </div>
            </form>
        </div>

        <!-- Appearance / Theme -->
        <div class="bg-base-100 border border-base-300 rounded-3xl shadow-sm p-8">
            <h2 class="text-xs font-black opacity-40 uppercase tracking-[0.2em] mb-6">Appearance</h2>
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold">Dark Mode</h3>
                    <p class="text-xs opacity-50 mt-1">Switch between light and dark themes for the application.</p>
                </div>
                <div class="form-control">
                    <input type="checkbox" id="theme-toggle" class="toggle toggle-primary toggle-lg" />
                </div>
            </div>
        </div>

        <script>
            const themeToggle = document.getElementById('theme-toggle');
            if (localStorage.getItem('theme') === 'dark') { themeToggle.checked = true; }
            themeToggle.addEventListener('change', function() {
                if (this.checked) {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.setAttribute('data-theme', 'light');
                    localStorage.setItem('theme', 'light');
                }
            });
        </script>

        <!-- Danger Zone (Delete Account) -->
        <div class="bg-error/5 rounded-3xl border border-error/20 p-8">
            <h2 class="text-xs font-black text-error uppercase tracking-[0.2em] mb-4">Danger Zone</h2>
            <p class="text-sm opacity-70 mb-6 font-medium">Once your account is deleted, all resources and data will be permanently deleted.</p>
            <button class="btn btn-error btn-outline rounded-xl font-black px-8" onclick="delete_modal.showModal()">Delete Account</button>
            <dialog id="delete_modal" class="modal">
                <div class="modal-box rounded-3xl p-8 border border-base-300 bg-base-100">
                    <h3 class="font-black text-2xl mb-4">Are you sure?</h3>
                    <form method="post" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('delete')
                        <div class="form-control mb-6">
                            <input type="password" name="password" placeholder="Confirm Password" class="input input-bordered w-full rounded-xl" required>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" class="btn btn-ghost rounded-xl" onclick="delete_modal.close()">Cancel</button>
                            <button type="submit" class="btn btn-error rounded-xl text-white font-black">Permanently Delete</button>
                        </div>
                    </form>
                </div>
            </dialog>
        </div>
    </div>
</div>
@endsection
