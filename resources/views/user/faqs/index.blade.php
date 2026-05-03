@extends('layouts.app')
@section('title', 'Knowledge Base')
@section('content')
    <div class="p-6 max-w-5xl mx-auto">
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-extrabold text-base-content mb-3">Knowledge Base</h1>
            <p class="text-lg text-base-content/70">Find answers to frequently asked questions.</p>
        </div>

        @if($faqs->isEmpty())
            <div class="bg-base-100 rounded-2xl shadow-sm border border-base-content/5 p-12 text-center">
                <x-heroicon-o-document-magnifying-glass class="w-16 h-16 mx-auto mb-4 text-base-content/20" />
                <h3 class="text-xl font-bold text-base-content mb-2">No FAQs available</h3>
                <p class="text-base-content/70">There are no articles published in the knowledge base yet.</p>
            </div>
        @else
            <div class="grid gap-4">
                @foreach($faqs as $faq)
                    <div class="collapse collapse-arrow bg-base-100 rounded-2xl shadow-sm border border-base-content/5 group transition-all hover:shadow-md">
                        <input type="radio" name="faq-accordion" />
                        <div class="collapse-title text-xl font-bold text-base-content group-hover:text-primary transition-colors flex items-center gap-3">
                            <x-heroicon-o-question-mark-circle class="w-6 h-6 text-primary flex-shrink-0" />
                            {{ $faq->question }}
                        </div>
                        <div class="collapse-content text-base-content/80 pt-0 pb-6 px-6">
                            <div class="prose prose-sm md:prose-base dark:prose-invert max-w-none mt-2">
                                {!! nl2br(e($faq->answer)) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        
        <div class="mt-12 text-center">
            <p class="text-base-content/70 mb-4">Didn't find what you were looking for?</p>
            <a href="{{ route('user.tickets.create') }}" class="btn btn-primary shadow-lg shadow-primary/30">
                <x-heroicon-o-envelope class="w-5 h-5 mr-2" /> Open a New Ticket
            </a>
        </div>
    </div>
@endsection
