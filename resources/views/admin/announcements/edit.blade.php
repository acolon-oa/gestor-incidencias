@extends('layouts.app')

@section('title', isset($item) ? 'Edit ' . 'announcement' : 'Create ' . 'announcement')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 tracking-tight">{{ isset($item) ? 'Edit' : 'Create' }} Announcements</h1>
    </div>

    <div class="card bg-base-100 shadow-sm border border-gray-100 rounded-2xl max-w-2xl">
        <div class="card-body">
            <form action="{{ isset($item) ? route('admin.announcements.update', $item->id) : route('admin.announcements.store') }}" method="POST">
                @csrf
                @if(isset($item)) @method('PUT') @endif
                
                
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-gray-700 capitalize">message</span></label>
                    <input type="text" name="message" value="{{ old('message', $item->message ?? '') }}" class="input input-bordered w-full bg-gray-50" />
                    @error('message')<span class="text-error text-xs mt-1">{{ $message }}</span>@enderror
                </div>
        

                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-gray-700 capitalize">type</span></label>
                    <input type="text" name="type" value="{{ old('type', $item->type ?? '') }}" class="input input-bordered w-full bg-gray-50" />
                    @error('type')<span class="text-error text-xs mt-1">{{ $message }}</span>@enderror
                </div>
        

                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-gray-700 capitalize">is active</span></label>
                    <input type="text" name="is_active" value="{{ old('is_active', $item->is_active ?? '') }}" class="input input-bordered w-full bg-gray-50" />
                    @error('is_active')<span class="text-error text-xs mt-1">{{ $message }}</span>@enderror
                </div>
        
                
                <div class="mt-6 flex gap-3">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('admin.announcements.index') }}" class="btn btn-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
