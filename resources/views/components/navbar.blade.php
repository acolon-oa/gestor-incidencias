            <nav class="flex items-center justify-between mb-6">
                <div class="text-xl font-bold">Welcome, {{ auth()->user()->name }}!
                </div>
                <div class="flex items-center gap-3">
                    <button class="btn btn-primary flex items-center gap-2">
                      {{-- <a href="{{ route('') }}"></a>--}}
                        <x-heroicon-o-plus class="w-5 h-5" />
                        <p>New Ticket</p>
                    </button>
                </div>
            </nav>
