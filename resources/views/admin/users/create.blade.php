@extends('layouts.app')

@section('title', 'New User')

@section('content')
<div class="flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-2xl">
        
        <!-- Header -->
        <div class="mb-8 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Create User</h1>
                <p class="text-gray-500 mt-1">Register a new member in the system.</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-primary hover:underline">Back to list</a>
        </div>

        @if($errors->any())
            <div class="alert alert-error mb-6 rounded-2xl border-none shadow-sm bg-red-50 text-red-800">
                <ul class="list-disc list-inside text-sm font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Single Professional Form Card -->
        <div class="card bg-base-100 shadow-xl shadow-gray-200/50 border border-gray-100 rounded-3xl overflow-hidden">
            <form action="{{ route('admin.users.store') }}" method="POST" class="p-10 space-y-8">
                @csrf
                
                <div class="space-y-6">
                    <!-- General Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label pb-1">
                                <span class="label-text font-bold text-gray-600">Full Name</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" 
                                class="input input-bordered focus:border-primary transition-all rounded-xl border-gray-200 bg-gray-50/30 focus:bg-white" 
                                placeholder="Enter full name" required>
                        </div>

                        <div class="form-control">
                            <label class="label pb-1">
                                <span class="label-text font-bold text-gray-600">Email Address</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" 
                                class="input input-bordered focus:border-primary transition-all rounded-xl border-gray-200 bg-gray-50/30 focus:bg-white" 
                                placeholder="name@company.com" required>
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label pb-1">
                                <span class="label-text font-bold text-gray-600">Password</span>
                            </label>
                            <input type="password" name="password" 
                                class="input input-bordered focus:border-primary transition-all rounded-xl border-gray-200 bg-gray-50/30 focus:bg-white" 
                                placeholder="••••••••" required>
                        </div>

                        <div class="form-control">
                            <label class="label pb-1">
                                <span class="label-text font-bold text-gray-600">Confirm Password</span>
                            </label>
                            <input type="password" name="password_confirmation" 
                                class="input input-bordered focus:border-primary transition-all rounded-xl border-gray-200 bg-gray-50/30 focus:bg-white" 
                                placeholder="••••••••" required>
                        </div>
                    </div>

                    <hr class="border-gray-100 my-2">

                    <!-- Permissions Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label pb-1">
                                <span class="label-text font-bold text-gray-600">Department</span>
                            </label>
                            <select name="department_id" class="select select-bordered focus:border-primary transition-all rounded-xl border-gray-200 bg-gray-50/30 focus:bg-white" required>
                                <option value="" disabled selected>Select Department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label pb-1">
                                <span class="label-text font-bold text-gray-600">Security Role</span>
                            </label>
                            <select name="role" class="select select-bordered focus:border-primary transition-all rounded-xl border-gray-200 bg-gray-50/30 focus:bg-white" required>
                                <option value="" disabled selected>Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-6">
                    <button type="submit" class="btn btn-primary px-10 rounded-xl font-bold shadow-lg shadow-primary/20">
                        Create Member
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
