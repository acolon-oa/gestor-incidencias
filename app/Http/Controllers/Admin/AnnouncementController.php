<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = \App\Models\Announcement::paginate(15);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'type' => 'required|string|max:50',
            'is_active' => 'nullable|boolean',
            'expires_at' => 'nullable|date'
        ]);
        
        if (!isset($validated['is_active'])) $validated['is_active'] = false;
        \App\Models\Announcement::create($validated);
        
        return redirect()->route('admin.announcements.index')->with('success', 'Announcement created successfully.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(\App\Models\Announcement $announcement)
    {
        $item = $announcement;
        return view('admin.announcements.edit', compact('item'));
    }

    public function update(Request $request, \App\Models\Announcement $announcement)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'type' => 'required|string|max:50',
            'is_active' => 'nullable|boolean',
            'expires_at' => 'nullable|date'
        ]);
        
        $validated['is_active'] = $request->has('is_active');

        $announcement->update($validated);
        return redirect()->route('admin.announcements.index')->with('success', 'Announcement updated successfully.');
    }

    public function destroy(\App\Models\Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('admin.announcements.index')->with('success', 'Announcement deleted successfully.');
    }
}
