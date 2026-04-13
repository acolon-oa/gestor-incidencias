<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class CannedResponseController extends Controller
{
    public function index()
    {
        $cannedResponses = \App\Models\CannedResponse::paginate(15);
        return view('admin.canned-responses.index', compact('cannedResponses'));
    }

    public function create()
    {
        return view('admin.canned-responses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string'
        ]);
        
        
        \App\Models\CannedResponse::create($validated);
        
        return redirect()->route('admin.canned-responses.index')->with('success', 'CannedResponse created successfully.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(\App\Models\CannedResponse $cannedResponse)
    {
        $item = $cannedResponse;
        return view('admin.canned-responses.edit', compact('item'));
    }

    public function update(Request $request, \App\Models\CannedResponse $cannedResponse)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string'
        ]);
        
        

        $cannedResponse->update($validated);
        return redirect()->route('admin.canned-responses.index')->with('success', 'CannedResponse updated successfully.');
    }

    public function destroy(\App\Models\CannedResponse $cannedResponse)
    {
        $cannedResponse->delete();
        return redirect()->route('admin.canned-responses.index')->with('success', 'CannedResponse deleted successfully.');
    }
}
