@extends('layouts.app')

@section('title', 'Create User')

@section('content')
    <div class="flex flex-col items-center justify-center min-h-[80vh]">
        <div class="w-full max-w-xl">
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Create New User</h1>
                <p class="text-gray-500 mt-2">Fill in the information below to register a new system user.</p>
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
                <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-bold text-gray-700">Full Name</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                            class="input input-bordered focus:border-primary transition-all rounded-xl" 
                            placeholder="John Doe" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-bold text-gray-700">Email Address</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                            class="input input-bordered focus:border-primary transition-all rounded-xl" 
                            placeholder="john@example.com" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-bold text-gray-700">Password</span>
                            </label>
                            <input type="password" name="password" 
                                class="input input-bordered focus:border-primary transition-all rounded-xl" 
                                placeholder="••••••••" required>
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-bold text-gray-700">Confirm Password</span>
                            </label>
                            <input type="password" name="password_confirmation" 
                                class="input input-bordered focus:border-primary transition-all rounded-xl" 
                                placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-bold text-gray-700">Department</span>
                            </label>
                            <select name="department_id" class="select select-bordered focus:border-primary transition-all rounded-xl" required>
                                <option value="" disabled selected>Select Department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-bold text-gray-700">Role</span>
                            </label>
                            <select name="role" class="select select-bordered focus:border-primary transition-all rounded-xl" required>
                                <option value="" disabled selected>Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-50">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost rounded-xl">Cancel</a>
                        <button type="submit" class="btn btn-primary px-10 rounded-xl shadow-lg shadow-primary/20">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
