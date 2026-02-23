@extends('layouts.app')

@section('title', 'Admin - Create Ticket')

@section('content')
<div class="w-full max-w-screen-2xl mx-auto py-8 px-8">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-6 border-b border-gray-100 pb-8">
        <div>
            <nav class="flex text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2 gap-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors">Admin</a>
                <span>/</span>
                <span class="text-gray-900">New Ticket</span>
            </nav>
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Create New Incident</h1>
            <p class="text-lg text-gray-500 mt-2 font-light">Internal ticket creation and assignment.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost hover:bg-gray-100 px-6 font-medium">Discard</a>
            <button type="submit" form="incident-form" class="btn btn-primary px-10 shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all font-bold">
                Submit Ticket
            </button>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-error mb-8 rounded-xl border-none shadow-sm bg-red-50 text-red-800">
            <div class="flex flex-col">
                <span class="font-bold">Entry Validation Failed</span>
                <ul class="list-disc list-inside text-sm mt-1 opacity-90">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form id="incident-form" action="{{ route('admin.tickets.store') }}" method="POST">
        @csrf
        <input type="hidden" name="status" value="open">
        
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-12">
            
            <!-- CORE CONTENT COLUMN -->
            <div class="xl:col-span-2 space-y-10">
                
                <!-- Subject -->
                <div class="form-control w-full group">
                    <label class="label mb-1">
                        <span class="label-text font-bold text-gray-700 text-sm uppercase tracking-wide group-focus-within:text-primary transition-colors">Incident Subject</span>
                    </label>
                    <input type="text" name="title" 
                        class="input input-lg w-full bg-white border-gray-200 focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all text-xl font-semibold placeholder-gray-300 rounded-xl" 
                        required value="{{ old('title') }}" 
                        placeholder="e.g., System failure in production">
                </div>

                <!-- Description -->
                <div class="form-control w-full group">
                    <label class="label mb-1">
                        <span class="label-text font-bold text-gray-700 text-sm uppercase tracking-wide group-focus-within:text-primary transition-colors">Detailed Description</span>
                    </label>
                    <textarea name="description" 
                        class="textarea w-full h-80 bg-white border-gray-200 focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all text-lg leading-relaxed placeholder-gray-300 rounded-xl p-6" 
                        required placeholder="What happened? Steps to reproduce...">{!! old('description') !!}</textarea>
                </div>

            </div>

            <!-- METADATA COLUMN -->
            <div class="space-y-8 h-full">
                <div class="bg-gray-50/50 border border-gray-100 rounded-2xl p-8 space-y-8 sticky top-8">
                    <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-4 mb-2">Configuration</h3>

                    <!-- Requester -->
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold text-gray-600 text-xs uppercase tracking-wider">Requester (On behalf of)</span>
                        </label>
                        <select name="user_id" class="select select-bordered bg-white border-gray-200 rounded-xl font-medium" required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ (old('user_id') == $user->id || auth()->id() == $user->id) ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Department -->
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold text-gray-600 text-xs uppercase tracking-wider">Target Department</span>
                        </label>
                        <select name="department_id" class="select select-bordered bg-white border-gray-200 rounded-xl font-medium" required>
                            <option value="" disabled selected>Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Priority -->
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold text-gray-600 text-xs uppercase tracking-wider">Priority</span>
                        </label>
                        <select name="priority" class="select select-bordered bg-white border-gray-200 rounded-xl font-medium" required>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>

                    <!-- Assignment -->
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold text-gray-600 text-xs uppercase tracking-wider">Assign to Agent</span>
                        </label>
                        <select name="assigned_to_id" class="select select-bordered bg-white border-gray-200 rounded-xl font-medium">
                            <option value="">Unassigned</option>
                            @foreach($users as $user)
                                @if($user->hasRole('admin'))
                                    <option value="{{ $user->id }}" {{ old('assigned_to_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label cursor-pointer justify-start gap-3">
                            <input type="checkbox" name="assign_to_me" class="checkbox checkbox-primary">
                            <span class="label-text font-bold text-gray-600">Assign to me</span>
                        </label>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
@endsection