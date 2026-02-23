@extends('layouts.app')
@section('title', 'Delete Ticket')
@section('content')
<div class="card bg-base-100 shadow p-6">
    <p class="text-gray-500">Direct ticket deletion is not available for users.</p>
    <a href="{{ route('user.dashboard') }}" class="btn btn-ghost mt-4">Back to Dashboard</a>
</div>
@endsection
