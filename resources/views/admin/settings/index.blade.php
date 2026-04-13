@extends('layouts.app')
@section('title', 'System Settings')
@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 tracking-tight">System Settings</h1>
    </div>
    @if(session('success'))
        <div class="alert alert-success mb-6 shadow-sm border-none bg-green-50 text-green-800">
            <span>{{ session('success') }}</span>
        </div>
    @endif
    <div class="card bg-base-100 shadow-sm border border-gray-100 rounded-2xl max-w-2xl">
        <div class="card-body">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <div class="form-control w-full mb-4">
                    <label class="label"><span class="label-text font-bold text-gray-700">App Name</span></label>
                    <input type="text" name="app_name" value="{{ $settings['app_name'] ?? config('app.name') }}" class="input input-bordered w-full bg-gray-50" />
                </div>
                <div class="form-control w-full mb-4">
                    <label class="label"><span class="label-text font-bold text-gray-700">Allow Registration</span></label>
                    <select name="allow_registration" class="select select-bordered w-full bg-gray-50">
                        <option value="1" {{ ($settings['allow_registration'] ?? '1') == '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ ($settings['allow_registration'] ?? '1') == '0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
@endsection