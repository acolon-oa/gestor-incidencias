@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

    <!-- Ticket Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 mt-5">
        <div class="card bg-base-100 shadow p-4">
            <div class="text-sm text-gray-500">Open Tickets</div>
            <div class="text-2xl font-bold">3</div>
        </div>
        <div class="card bg-base-100 shadow p-4">
            <div class="text-sm text-gray-500">Tickets Pending My Action</div>
            <div class="text-2xl font-bold">1</div>
        </div>
        <div class="card bg-base-100 shadow p-4">
            <div class="text-sm text-gray-500">Recently Resolved Tickets</div>
            <div class="text-2xl font-bold">5</div>
        </div>
    </div>

    <!-- Incidences Card -->
    <div class="card bg-base-100 shadow p-4 mt-2">
        <div class="flex justify-between items-center mb-4">
            <div class="text-md font-bold ml-3 text-gray-500">All Tickets</div>
            <div class="flex items-center gap-2 mb-3 mt-3">
                <input type="text" placeholder="Ticket ID" class="input input-md input-bordered w-36" />
                <select class="select select-md select-bordered w-36">
                    <option disabled selected>Status</option>
                    <option>All</option>
                    <option>Open</option>
                    <option>Pending</option>
                    <option>In Progress</option>
                    <option>Resolved</option>
                </select>
                <button class="btn btn-md btn-primary">Search</button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Last Updated</th>
                        <th>Department</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#9145</td>
                        <td>Marketing Printer Issue</td>
                        <td><span class="badge badge-error">Open</span></td>
                        <td>15m ago</td>
                        <td>Marketing</td>
                    </tr>
                    <!-- resto -->
                </tbody>
            </table>
        </div>
    </div>

@endsection
