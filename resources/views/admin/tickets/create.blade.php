@extends('layouts.app')

@section('title', 'Admin - Create Ticket')

@section('content')
<div class="w-full max-w-screen-2xl mx-auto py-8">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-6 border-b border-base-content/5 pb-8">
        <div>
            <nav class="flex text-xs font-semibold uppercase tracking-wider text-base-content/40 mb-2 gap-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors">Admin</a>
                <span>/</span>
                <span class="text-base-content font-bold">New Ticket</span>
            </nav>
            <h1 class="text-4xl font-extrabold text-base-content tracking-tight">Create New Incident</h1>
            <p class="text-lg text-base-content/40 mt-2 font-light">Internal ticket creation and assignment.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost px-6 font-medium">Discard</a>
            <button type="submit" form="incident-form" class="btn btn-primary px-10 shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all font-bold">
                Submit Ticket
            </button>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-error mb-8 rounded-2xl border-none shadow-sm bg-red-500/10 text-red-500">
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

    <form id="incident-form" action="{{ route('admin.tickets.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="status" value="open">
        
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-12">
            
            <!-- CORE CONTENT COLUMN -->
            <div class="xl:col-span-2 space-y-10">
                
                <!-- Subject -->
                <div class="form-control w-full group">
                    <label class="label mb-1">
                        <span class="label-text font-bold text-base-content/60 text-sm uppercase tracking-wide group-focus-within:text-primary transition-colors">Incident Subject</span>
                    </label>
                    <input type="text" name="title" 
                        class="input input-lg w-full bg-base-100 border-base-content/10 focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all text-xl font-semibold placeholder-base-content/20 rounded-2xl" 
                        required value="{{ old('title') }}" 
                        placeholder="e.g., System failure in production">
                </div>

                <!-- Description -->
                <div class="form-control w-full group">
                    <label class="label mb-1">
                        <span class="label-text font-bold text-base-content/60 text-sm uppercase tracking-wide group-focus-within:text-primary transition-colors">Detailed Description</span>
                    </label>
                    <textarea name="description" 
                        class="textarea w-full h-80 bg-base-100 border-base-content/10 focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all text-lg leading-relaxed placeholder-base-content/20 rounded-2xl p-6" 
                        required placeholder="What happened? Steps to reproduce...">{!! old('description') !!}</textarea>
                </div>

                <!-- Attachments -->
                <div class="form-control w-full group">
                    <label class="label mb-1">
                        <span class="label-text font-bold text-base-content/60 text-sm uppercase tracking-wide group-focus-within:text-primary transition-colors">Internal Documentation / Evidence</span>
                    </label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-base-content/10 border-dashed rounded-2xl cursor-pointer bg-base-100 hover:bg-base-200 transition-all group-hover:border-primary/50">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <x-heroicon-o-cloud-arrow-up class="w-8 h-8 text-base-content/30 group-hover:text-primary transition-colors mb-2" />
                                <p class="mb-2 text-sm text-base-content/60 font-bold">Upload attachments (evidence, screenshots, logs)</p>
                                <p class="text-[10px] text-base-content/30 uppercase font-black tracking-widest">PNG, JPG, PDF (MAX. 10MB)</p>
                            </div>
                            <input name="attachments[]" type="file" class="hidden" multiple />
                        </label>
                    </div>
                </div>

            </div>

            <!-- METADATA COLUMN -->
            <div class="space-y-8 h-full">
                <div class="bg-base-100 border border-base-content/5 rounded-3xl p-8 space-y-8 sticky top-8">
                    <h3 class="text-sm font-black text-base-content/40 uppercase tracking-widest border-b border-base-content/5 pb-4 mb-2">Configuration</h3>

                    <!-- Department -->
                    <div class="w-full">
                        <div class="mb-2"><span class="font-bold text-base-content/60 text-xs uppercase tracking-wider">Target Department</span></div>
                        <select name="department_id" class="select select-bordered bg-base-100 border-base-content/10 rounded-xl font-medium w-full" required>
                            <option value="" disabled selected>Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Priority -->
                    <div class="w-full">
                        <div class="mb-2"><span class="font-bold text-base-content/60 text-xs uppercase tracking-wider">Priority</span></div>
                        <select name="priority" class="select select-bordered bg-base-100 border-base-content/10 rounded-xl font-medium w-full" required>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
@endsection