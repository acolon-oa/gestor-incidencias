@extends('layouts.app')

@section('title', 'Report Incident')

@section('content')
<div class="w-full max-w-screen-2xl mx-auto py-8 px-8">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-6 border-b border-gray-100 pb-8">
        <div>
            <nav class="flex text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2 gap-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors">Console</a>
                <span>/</span>
                <span class="text-gray-900">Tickets</span>
            </nav>
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Report New Incident</h1>
            <p class="text-lg text-gray-500 mt-2 font-light">Categorize and detail the issue to ensure rapid resolution.</p>
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
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
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
                        placeholder="e.g., Database connectivity issues in production">
                    <label class="label">
                        <span class="label-text-alt text-gray-400">Provide a concise yet descriptive title for the issue.</span>
                    </label>
                </div>

                <!-- Description -->
                <div class="form-control w-full group">
                    <label class="label mb-1">
                        <span class="label-text font-bold text-gray-700 text-sm uppercase tracking-wide group-focus-within:text-primary transition-colors">Detailed Description</span>
                    </label>
                    <textarea name="description" 
                        class="textarea w-full h-80 bg-white border-gray-200 focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all text-lg leading-relaxed placeholder-gray-300 rounded-xl p-6" 
                        required placeholder="What happened? What are the reproduction steps? What is the impact?">{!! old('description') !!}</textarea>
                    <div class="flex justify-between items-center mt-2 px-1">
                        <span class="text-xs text-gray-400 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Supports standard plaintext with line breaks.
                        </span>
                    </div>
                </div>

            </div>

            <!-- METADATA COLUMN -->
            <div class="space-y-8 h-full">
                
                <div class="bg-gray-50/50 border border-gray-100 rounded-2xl p-8 space-y-8 sticky top-8">
                    
                    <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-4 mb-2">Contextual Data</h3>

                    <!-- Requester -->
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold text-gray-600 text-xs uppercase tracking-wider">Affected User</span>
                        </label>
                        <select name="user_id" class="select select-bordered bg-white border-gray-200 focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all rounded-xl font-medium" required>
                            <option value="" disabled selected>Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Department -->
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold text-gray-600 text-xs uppercase tracking-wider">Originating Department</span>
                        </label>
                        <select name="department_id" class="select select-bordered bg-white border-gray-200 focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all rounded-xl font-medium" required>
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
                            <span class="label-text font-bold text-gray-600 text-xs uppercase tracking-wider">Severity Level</span>
                        </label>
                        <div class="grid grid-cols-2 gap-2 mt-2">
                            <label class="cursor-pointer">
                                <input type="radio" name="priority" value="low" class="peer hidden" {{ old('priority') == 'low' ? 'checked' : '' }}>
                                <div class="p-3 border text-center rounded-xl font-semibold text-xs border-gray-100 bg-white text-gray-400 peer-checked:bg-green-50 peer-checked:border-green-200 peer-checked:text-green-700 transition-all hover:bg-gray-50">
                                    LOW
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="priority" value="medium" class="peer hidden" {{ old('priority', 'medium') == 'medium' ? 'checked' : '' }}>
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
                            <label class="cursor-pointer">
                                <input type="radio" name="priority" value="urgent" class="peer hidden" {{ old('priority') == 'urgent' ? 'checked' : '' }}>
                                <div class="p-3 border text-center rounded-xl font-semibold text-xs border-gray-100 bg-white text-gray-400 peer-checked:bg-red-50 peer-checked:border-red-200 peer-checked:text-red-700 transition-all hover:bg-gray-50">
                                    URGENT
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Visual Separator -->
                    <div class="pt-6 border-t border-gray-100 mt-4">
                        <div class="p-4 bg-primary/5 rounded-xl border border-primary/10">
                            <p class="text-[10px] font-black uppercase tracking-widest text-primary mb-1">Direct Assignment</p>
                            <p class="text-xs text-gray-500 leading-relaxed">By default, this ticket will be queued for the selected department's triage team.</p>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </form>
</div>
@endsection