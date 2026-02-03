@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <div class="flex flex-col items-center justify-center min-h-[80vh]">
        <div class="w-full max-w-xl">
            <div class="mb-8 text-center text-center">
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Edit User</h1>
                <p class="text-gray-500 mt-2">Update information for <span class="font-bold text-gray-800">{{ $user->name }}</span>.</p>
            </div>

            @if($errors->any())
                <div class="alert alert-error mb-6 shadow-sm rounded-xl">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card bg-base-100 shadow-xl border border-gray-100 p-8 rounded-2xl">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-bold text-gray-700">Full Name</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                            class="input input-bordered focus:border-primary transition-all rounded-xl" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-bold text-gray-700">Email Address</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                            class="input input-bordered focus:border-primary transition-all rounded-xl" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-bold text-gray-700">New Password</span>
                            </label>
                            <input type="password" name="password" 
                                class="input input-bordered focus:border-primary transition-all rounded-xl" 
                                placeholder="Leave blank to keep current">
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-bold text-gray-700">Confirm Password</span>
                            </label>
                            <input type="password" name="password_confirmation" 
                                class="input input-bordered focus:border-primary transition-all rounded-xl" 
                                placeholder="Leave blank to keep current">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-bold text-gray-700">Department</span>
                            </label>
                            <select name="department_id" class="select select-bordered focus:border-primary transition-all rounded-xl" required>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-bold text-gray-700">Role</span>
                            </label>
                            <select name="role" class="select select-bordered focus:border-primary transition-all rounded-xl" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role', $user->roles->first()?->name) == $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-50">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost rounded-xl">Cancel</a>
                        <button type="submit" class="btn btn-primary px-10 rounded-xl shadow-lg shadow-primary/20">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
