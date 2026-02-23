@extends('layouts.app')
@section('title', 'Edit Ticket')
@section('content')
<div class="card bg-base-100 shadow p-6">
    <p class="text-gray-500">Editing user tickets is not available. Please contact an administrator.</p>
    <a href="{{ route('user.dashboard') }}" class="btn btn-ghost mt-4">Back to Dashboard</a>
</div>
@endsection
