const fs = require('fs');
const path = require('path');

const baseDir = path.join(__dirname, 'resources', 'views', 'admin');

const resources = [
    { name: 'departments', title: 'Departments', singular: 'department', fields: ['name', 'description'] },
    { name: 'canned-responses', title: 'Canned Responses', singular: 'canned_response', fields: ['title', 'content'] },
    { name: 'announcements', title: 'Announcements', singular: 'announcement', fields: ['message', 'type', 'is_active'] },
    { name: 'audit-logs', title: 'Audit Logs', singular: 'audit_log', isReadonly: true, fields: ['type', 'old_value', 'new_value', 'created_at'] }
];

for (const res of resources) {
    const dir = path.join(baseDir, res.name);
    if (!fs.existsSync(dir)) {
        fs.mkdirSync(dir, { recursive: true });
    }

    const titleLower = res.title.toLowerCase();

    // INDEX
    let indexContent = `@extends('layouts.app')

@section('title', '${res.title}')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">${res.title}</h1>
            <p class="text-sm text-gray-500">Manage all ${titleLower} here.</p>
        </div>
        ${res.isReadonly ? '' : `<a href="{{ route('admin.${res.name}.create') }}" class="btn btn-primary shadow-lg shadow-primary/20 gap-2">
            <x-heroicon-o-plus-circle class="w-5 h-5" /> New
        </a>`}
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-6 shadow-sm border-none bg-green-50 text-green-800">
            <x-heroicon-o-check-circle class="w-6 h-6" />
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error mb-6 shadow-sm border-none bg-red-50 text-red-800">
            <x-heroicon-o-x-circle class="w-6 h-6" />
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="card bg-base-100 shadow-sm border border-gray-100 overflow-hidden rounded-2xl">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-400">
                        <th class="font-black uppercase text-[10px] tracking-widest pl-6">ID</th>
                        ${res.fields.map(f => `<th class="font-black uppercase text-[10px] tracking-widest">${f.replace('_', ' ')}</th>`).join('\n                        ')}
                        <th class="font-black uppercase text-[10px] tracking-widest pr-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($${res.name.replace('-', '_')} as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="pl-6 font-bold text-gray-300">#{{ $item->id }}</td>
                            ${res.fields.map(f => `<td class="text-sm text-gray-500">{{ Str::limit($item->${f}, 50) }}</td>`).join('\n                            ')}
                            <td class="pr-6 text-right">
                                <div class="flex justify-end gap-1">
                                    ${res.isReadonly ? '' : `
                                    <a href="{{ route('admin.${res.name}.edit', $item->id) }}" class="btn btn-ghost btn-xs text-primary font-bold">Edit</a>
                                    <form action="{{ route('admin.${res.name}.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-xs text-error font-bold">Delete</button>
                                    </form>
                                    `}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($${res.name.replace('-', '_')}->hasPages())
            <div class="p-4 bg-gray-50/30 border-t border-gray-100">
                {{ $${res.name.replace('-', '_')}->links() }}
            </div>
        @endif
    </div>
@endsection
`;

    fs.writeFileSync(path.join(dir, 'index.blade.php'), indexContent);

    if (!res.isReadonly) {
        // CREATE
        let formFields = res.fields.map(f => `
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-gray-700 capitalize">${f.replace('_', ' ')}</span></label>
                    <input type="text" name="${f}" value="{{ old('${f}', $item->${f} ?? '') }}" class="input input-bordered w-full bg-gray-50" />
                    @error('${f}')<span class="text-error text-xs mt-1">{{ $message }}</span>@enderror
                </div>
        `).join('\n');

        let formContent = `@extends('layouts.app')

@section('title', isset($item) ? 'Edit ' . '${res.singular}' : 'Create ' . '${res.singular}')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 tracking-tight">{{ isset($item) ? 'Edit' : 'Create' }} ${res.title}</h1>
    </div>

    <div class="card bg-base-100 shadow-sm border border-gray-100 rounded-2xl max-w-2xl">
        <div class="card-body">
            <form action="{{ isset($item) ? route('admin.${res.name}.update', $item->id) : route('admin.${res.name}.store') }}" method="POST">
                @csrf
                @if(isset($item)) @method('PUT') @endif
                
                ${formFields}
                
                <div class="mt-6 flex gap-3">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('admin.${res.name}.index') }}" class="btn btn-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
`;
        fs.writeFileSync(path.join(dir, 'create.blade.php'), formContent.replace(/\$item->/g, ""));
        fs.writeFileSync(path.join(dir, 'edit.blade.php'), formContent);
    }
}

// System Settings logic
const settingsDir = path.join(baseDir, 'settings');
if (!fs.existsSync(settingsDir)) {
    fs.mkdirSync(settingsDir, { recursive: true });
}

let settingsContent = `@extends('layouts.app')
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
@endsection`;
fs.writeFileSync(path.join(settingsDir, 'index.blade.php'), settingsContent);

console.log("Views generated successfully!");
