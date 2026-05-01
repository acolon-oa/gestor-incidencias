@extends('layouts.app')

@section('title', 'Report Incident')

@section('content')
<div class="w-full max-w-screen-2xl mx-auto py-8">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-6 border-b border-base-content/5 pb-8">
        <div>
            <nav class="flex text-xs font-semibold uppercase tracking-wider text-base-content/40 mb-2 gap-2">
                <a href="{{ route('user.dashboard') }}" class="hover:text-primary transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-base-content font-bold">New Ticket</span>
            </nav>
            <h1 class="text-4xl font-extrabold text-base-content tracking-tight">Report New Incident</h1>
            <p class="text-lg text-base-content/40 mt-2 font-light">Describe the issue and our team will help you as soon as possible.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('user.dashboard') }}" class="btn btn-ghost px-6 font-medium">Discard</a>
            <button type="submit" form="incident-form" class="btn btn-primary px-10 shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all font-bold" id="submit-btn">
                <span class="loading loading-spinner loading-sm hidden" id="submit-spinner"></span>
                <span id="submit-text">Submit Ticket</span>
            </button>
        </div>
    </div>

    <script>
        document.getElementById('incident-form').addEventListener('submit', function() {
            document.getElementById('submit-btn').disabled = true;
            document.getElementById('submit-spinner').classList.remove('hidden');
            document.getElementById('submit-text').innerText = 'Uploading...';
        });
    </script>

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

    <form id="incident-form" action="{{ route('user.tickets.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-12">
            
            <!-- CORE CONTENT COLUMN -->
            <div class="xl:col-span-2 space-y-10">
                
                <!-- Subject -->
                <div class="form-control w-full group">
                    <label class="label mb-1">
                        <span class="label-text font-bold text-base-content/60 text-sm uppercase tracking-wide group-focus-within:text-primary transition-colors">Incident Subject</span>
                    </label>
                    <input type="text" name="subject" 
                        class="input input-lg w-full bg-base-100 border-base-content/10 focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all text-xl font-semibold placeholder-base-content/20 rounded-2xl" 
                        required value="{{ old('subject') }}" 
                        placeholder="e.g., Cannot connect to the printer">
                </div>

                <!-- Description -->
                <div class="form-control w-full group">
                    <label class="label mb-1">
                        <span class="label-text font-bold text-base-content/60 text-sm uppercase tracking-wide group-focus-within:text-primary transition-colors">Detailed Description</span>
                    </label>
                    <textarea name="description" 
                        class="textarea w-full h-80 bg-base-100 border-base-content/10 focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all text-lg leading-relaxed placeholder-base-content/20 rounded-2xl p-6" 
                        required placeholder="Please explain what is happening...">{!! old('description') !!}</textarea>
                </div>

                <!-- Attachments -->
                <div class="form-control w-full group">
                    <label class="label mb-1">
                        <span class="label-text font-bold text-base-content/60 text-sm uppercase tracking-wide group-focus-within:text-primary transition-colors">Attachments (Optional)</span>
                    </label>
                    <div class="flex flex-col gap-4">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-base-content/10 border-dashed rounded-2xl cursor-pointer bg-base-100 hover:bg-base-200 transition-all group-hover:border-primary/50" id="dropzone">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <x-heroicon-o-cloud-arrow-up class="w-8 h-8 text-base-content/30 group-hover:text-primary transition-colors mb-2" id="upload-icon" />
                                <p class="mb-2 text-sm text-base-content/60 font-bold" id="upload-text">Click to upload or drag and drop</p>
                                <p class="text-[10px] text-base-content/30 uppercase font-black tracking-widest">PNG, JPG, PDF (MAX. 10MB)</p>
                            </div>
                            <input name="attachments[]" type="file" id="file-input" class="hidden" multiple />
                        </label>
                        
                        <!-- File List Preview -->
                        <div id="file-list" class="grid grid-cols-1 sm:grid-cols-2 gap-3 hidden">
                            <!-- Files will be injected here -->
                        </div>
                    </div>
                </div>

                <script>
                    document.getElementById('file-input').addEventListener('change', function(e) {
                        const fileList = document.getElementById('file-list');
                        const uploadText = document.getElementById('upload-text');
                        const uploadIcon = document.getElementById('upload-icon');
                        const dropzone = document.getElementById('dropzone');
                        
                        fileList.innerHTML = '';
                        
                        if (this.files.length > 0) {
                            fileList.classList.remove('hidden');
                            uploadText.innerHTML = `<span class="text-primary">${this.files.length} files selected</span>`;
                            uploadIcon.classList.add('text-primary');
                            dropzone.classList.add('border-primary/30', 'bg-primary/5');
                            
                            Array.from(this.files).forEach(file => {
                                const size = (file.size / 1024 / 1024).toFixed(2);
                                const fileDiv = document.createElement('div');
                                fileDiv.className = 'flex items-center gap-3 p-3 bg-base-100 border border-base-content/5 rounded-xl shadow-sm';
                                fileDiv.innerHTML = `
                                    <div class="w-10 h-10 rounded-lg bg-base-200 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-base-content/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <div class="overflow-hidden">
                                        <div class="text-xs font-bold text-base-content truncate">${file.name}</div>
                                        <div class="text-[10px] font-medium text-base-content/40 uppercase tracking-widest">${size} MB</div>
                                    </div>
                                `;
                                fileList.appendChild(fileDiv);
                            });
                        } else {
                            fileList.classList.add('hidden');
                            uploadText.innerText = 'Click to upload or drag and drop';
                            uploadIcon.classList.remove('text-primary');
                            dropzone.classList.remove('border-primary/30', 'bg-primary/5');
                        }
                    });
                </script>

            </div>

            <!-- METADATA COLUMN -->
            <div class="space-y-8 h-full">
                <div class="bg-base-100 border border-base-content/5 rounded-3xl p-8 space-y-8 sticky top-8">
                    <h3 class="text-sm font-black text-base-content/40 uppercase tracking-widest border-b border-base-content/5 pb-4 mb-2">Details</h3>

                    <!-- Department -->
                    <div class="w-full">
                        <div class="mb-2">
                            <span class="font-bold text-base-content/60 text-xs uppercase tracking-wider">Department</span>
                        </div>
                        <select name="department" class="select select-bordered bg-base-100 border-base-content/10 rounded-xl font-medium w-full" required>
                            <option value="" disabled selected>Select Department</option>
                            @foreach(\App\Models\Department::all() as $department)
                                <option value="{{ $department->name }}" {{ old('department') == $department->name ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Priority -->
                    <div class="w-full">
                        <div class="mb-2">
                            <span class="font-bold text-base-content/60 text-xs uppercase tracking-wider">Severity</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="cursor-pointer">
                                <input type="radio" name="priority" value="low" class="peer hidden" {{ old('priority', 'low') == 'low' ? 'checked' : '' }}>
                                <div class="p-3 border text-center rounded-xl font-black text-[10px] border-base-content/10 bg-base-200/50 text-base-content/40 peer-checked:bg-success/20 peer-checked:border-success/40 peer-checked:text-success transition-all hover:bg-base-200">
                                    LOW
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="priority" value="medium" class="peer hidden" {{ old('priority') == 'medium' ? 'checked' : '' }}>
                                <div class="p-3 border text-center rounded-xl font-black text-[10px] border-base-content/10 bg-base-200/50 text-base-content/40 peer-checked:bg-info/20 peer-checked:border-info/40 peer-checked:text-info transition-all hover:bg-base-200">
                                    MED
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="priority" value="high" class="peer hidden" {{ old('priority') == 'high' ? 'checked' : '' }}>
                                <div class="p-3 border text-center rounded-xl font-black text-[10px] border-base-content/10 bg-base-200/50 text-base-content/40 peer-checked:bg-warning/20 peer-checked:border-warning/40 peer-checked:text-warning transition-all hover:bg-base-200">
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
