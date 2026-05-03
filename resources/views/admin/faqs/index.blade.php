@extends('layouts.app')
@section('title', 'Knowledge Base')
@section('content')
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-base-content">Knowledge Base</h1>
                <p class="text-base-content/70 mt-1">Manage frequently asked questions</p>
            </div>
            <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary shadow-lg shadow-primary/30">
                <x-heroicon-o-plus class="w-5 h-5 mr-1" /> Add FAQ
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success mb-6 shadow-sm">
                <x-heroicon-o-check-circle class="w-6 h-6" />
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-base-100 rounded-2xl shadow-sm border border-base-content/5 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-base-200/50 text-base-content/70 border-b border-base-content/5">
                        <tr>
                            <th class="font-semibold text-sm">Question</th>
                            <th class="font-semibold text-sm">Status</th>
                            <th class="font-semibold text-sm text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($faqs as $faq)
                            <tr class="hover:bg-base-200/30 transition-colors border-b border-base-content/5 last:border-0">
                                <td class="py-4">
                                    <div class="font-bold text-base-content">{{ $faq->question }}</div>
                                </td>
                                <td>
                                    @if($faq->is_active)
                                        <div class="badge badge-success badge-sm font-bold gap-1"><x-heroicon-o-check class="w-3 h-3"/> Active</div>
                                    @else
                                        <div class="badge badge-error badge-sm font-bold gap-1"><x-heroicon-o-x-mark class="w-3 h-3"/> Inactive</div>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-square btn-sm btn-ghost text-base-content/70 hover:text-primary transition-colors" title="Edit">
                                            <x-heroicon-o-pencil-square class="w-5 h-5" />
                                        </a>
                                        <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this FAQ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-square btn-sm btn-ghost text-base-content/70 hover:text-error transition-colors" title="Delete">
                                                <x-heroicon-o-trash class="w-5 h-5" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-12 text-base-content/50">
                                    <x-heroicon-o-document-text class="w-12 h-12 mx-auto mb-3 opacity-20" />
                                    No FAQs created yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
