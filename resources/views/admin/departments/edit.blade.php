@extends('layouts.app')

@section('title', isset($item) ? 'Edit ' . 'department' : 'Create ' . 'department')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 tracking-tight">{{ isset($item) ? 'Edit' : 'Create' }} Departments</h1>
    </div>

    <div class="card bg-base-100 shadow-sm border border-gray-100 rounded-2xl max-w-2xl">
        <div class="card-body">
            <form action="{{ isset($item) ? route('admin.departments.update', $item->id) : route('admin.departments.store') }}" method="POST">
                @csrf
                @if(isset($item)) @method('PUT') @endif
                
                
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-gray-700 capitalize">name</span></label>
                    <input type="text" name="name" value="{{ old('name', $item->name ?? '') }}" class="input input-bordered w-full bg-gray-50" />
                    @error('name')<span class="text-error text-xs mt-1">{{ $message }}</span>@enderror
                </div>
        

                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-gray-700 capitalize">description</span></label>
                    <input type="text" name="description" value="{{ old('description', $item->description ?? '') }}" class="input input-bordered w-full bg-gray-50" />
                    @error('description')<span class="text-error text-xs mt-1">{{ $message }}</span>@enderror
                </div>
        
                
                <div class="mt-6 flex gap-3">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('admin.departments.index') }}" class="btn btn-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
