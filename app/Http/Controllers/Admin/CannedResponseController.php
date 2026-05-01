<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CannedResponseController extends Controller
{
    public function index()
    {
        $canned_responses = \App\Models\CannedResponse::paginate(15);
        return view('admin.canned-responses.index', compact('canned_responses'));
    }

    public function create()
    {
        return view('admin.canned-responses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required|string',
            'content' => 'required|string',
        ]);

        \App\Models\CannedResponse::create($validated);

        return redirect()->route('admin.canned-responses.index')->with('success', 'Canned response created successfully.');
    }

    public function edit(\App\Models\CannedResponse $cannedResponse)
    {
        $item = $cannedResponse;
        return view('admin.canned-responses.edit', compact('item'));
    }

    public function update(Request $request, \App\Models\CannedResponse $cannedResponse)
    {
        $validated = $request->validate([
            'title'   => 'required|string',
            'content' => 'required|string',
        ]);

        $cannedResponse->update($validated);
        return redirect()->route('admin.canned-responses.index')->with('success', 'Canned response updated successfully.');
    }

    public function destroy(\App\Models\CannedResponse $cannedResponse)
    {
        $cannedResponse->delete();
        return redirect()->route('admin.canned-responses.index')->with('success', 'Canned response deleted successfully.');
    }
}
