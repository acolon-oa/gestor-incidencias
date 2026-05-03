@extends('layouts.app')
@section('title', 'Create FAQ')
@section('content')
    <div class="p-6 max-w-4xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-base-content">Create FAQ</h1>
                <p class="text-base-content/70 mt-1">Add a new article to the knowledge base.</p>
            </div>
            <a href="{{ route('admin.faqs.index') }}" class="btn btn-ghost">
                <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" /> Back
            </a>
        </div>

        <div class="bg-base-100 rounded-2xl shadow-sm border border-base-content/5 overflow-hidden">
            <form action="{{ route('admin.faqs.store') }}" method="POST" class="p-6 md:p-8">
                @csrf

                <div class="mb-6">
                    <label class="label"><span class="label-text font-bold text-base-content">Question</span></label>
                    <input type="text" name="question" value="{{ old('question') }}" class="input input-bordered w-full bg-base-200/50 focus:bg-base-100 transition-colors" required placeholder="E.g., How do I reset my password?">
                    @error('question') <span class="text-error text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label class="label"><span class="label-text font-bold text-base-content">Answer</span></label>
                    <textarea name="answer" class="textarea textarea-bordered w-full h-40 bg-base-200/50 focus:bg-base-100 transition-colors" required placeholder="Provide a detailed answer...">{{ old('answer') }}</textarea>
                    @error('answer') <span class="text-error text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="mb-8">
                    <label class="label cursor-pointer justify-start gap-4">
                        <span class="label-text font-bold text-base-content">Status</span>
                        <input type="checkbox" name="is_active" class="toggle toggle-success" checked value="1" />
                    </label>
                    <p class="text-sm text-base-content/50 mt-1">If inactive, it won't be visible to users.</p>
                </div>

                <div class="flex justify-end pt-4 border-t border-base-content/10">
                    <button type="submit" class="btn btn-primary shadow-lg shadow-primary/30 min-w-[150px]">
                        <x-heroicon-o-check class="w-5 h-5 mr-2" /> Save FAQ
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
