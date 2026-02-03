@extends('layouts.app')

@section('title', 'Report Incident')

@section('content')
<div class="w-full max-w-screen-2xl mx-auto py-8 px-8">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-6 border-b border-gray-100 pb-8">
        <div>
            <nav class="flex text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2 gap-2">
                <a href="{{ route('user.dashboard') }}" class="hover:text-primary transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-gray-900">New Ticket</span>
            </nav>
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Report New Incident</h1>
            <p class="text-lg text-gray-500 mt-2 font-light">Describe the issue and our team will help you as soon as possible.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('user.dashboard') }}" class="btn btn-ghost hover:bg-gray-100 px-6 font-medium">Discard</a>
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

    <form id="incident-form" action="{{ route('user.tickets.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-12">
            
            <!-- CORE CONTENT COLUMN -->
            <div class="xl:col-span-2 space-y-10">
                
                <!-- Subject -->
                <div class="form-control w-full group">
                    <label class="label mb-1">
                        <span class="label-text font-bold text-gray-700 text-sm uppercase tracking-wide group-focus-within:text-primary transition-colors">Incident Subject</span>
                    </label>
                    <input type="text" name="subject" 
                        class="input input-lg w-full bg-white border-gray-200 focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all text-xl font-semibold placeholder-gray-300 rounded-xl" 
                        required value="{{ old('subject') }}" 
                        placeholder="e.g., Cannot connect to the printer">
                </div>

                <!-- Description -->
                <div class="form-control w-full group">
                    <label class="label mb-1">
                        <span class="label-text font-bold text-gray-700 text-sm uppercase tracking-wide group-focus-within:text-primary transition-colors">Detailed Description</span>
                    </label>
                    <textarea name="description" 
                        class="textarea w-full h-80 bg-white border-gray-200 focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all text-lg leading-relaxed placeholder-gray-300 rounded-xl p-6" 
                        required placeholder="Please explain what is happening...">{!! old('description') !!}</textarea>
                </div>

            </div>

            <!-- METADATA COLUMN -->
            <div class="space-y-8 h-full">
                <div class="bg-gray-50/50 border border-gray-100 rounded-2xl p-8 space-y-8 sticky top-8">
                    <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-4 mb-2">Details</h3>

                    <!-- Department -->
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold text-gray-600 text-xs uppercase tracking-wider">Department</span>
                        </label>
                        <select name="department" class="select select-bordered bg-white border-gray-200 rounded-xl font-medium" required>
                            <option value="" disabled selected>Select Department</option>
                            @foreach(\App\Models\Department::all() as $department)
                                <option value="{{ $department->name }}" {{ old('department') == $department->name ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Priority -->
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold text-gray-600 text-xs uppercase tracking-wider">Severity</span>
                        </label>
                        <div class="grid grid-cols-2 gap-2 mt-2">
                             <label class="cursor-pointer">
                                <input type="radio" name="priority" value="low" class="peer hidden" {{ old('priority', 'low') == 'low' ? 'checked' : '' }}>
                                <div class="p-3 border text-center rounded-xl font-semibold text-xs border-gray-100 bg-white text-gray-400 peer-checked:bg-green-50 peer-checked:border-green-200 peer-checked:text-green-700 transition-all hover:bg-gray-50">
                                    LOW
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="priority" value="medium" class="peer hidden" {{ old('priority') == 'medium' ? 'checked' : '' }}>
                                <div class="p-3 border text-center rounded-xl font-semibold text-xs border-gray-100 bg-white text-gray-400 peer-checked:bg-blue-50 peer-checked:border-blue-200 peer-checked:text-blue-700 transition-all hover:bg-gray-50">
                                    NORMAL
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="priority" value="high" class="peer hidden" {{ old('priority') == 'high' ? 'checked' : '' }}>
                                <div class="p-3 border text-center rounded-xl font-semibold text-xs border-gray-100 bg-white text-gray-400 peer-checked:bg-orange-50 peer-checked:border-orange-200 peer-checked:text-orange-700 transition-all hover:bg-gray-50">
                                    HIGH
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
