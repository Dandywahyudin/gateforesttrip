@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between gap-4">
        <div class="flex flex-1 items-center justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center rounded-full border border-gray-200 bg-white px-4 py-2 text-xs font-black uppercase tracking-[0.2em] text-gray-400">
                    {{ __('Previous') }}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center rounded-full border border-primary/20 bg-white px-4 py-2 text-xs font-black uppercase tracking-[0.2em] text-primary transition hover:border-primary hover:bg-primary hover:text-white">
                    {{ __('Previous') }}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center rounded-full border border-primary/20 bg-white px-4 py-2 text-xs font-black uppercase tracking-[0.2em] text-primary transition hover:border-primary hover:bg-primary hover:text-white">
                    {{ __('Next') }}
                </a>
            @else
                <span class="inline-flex items-center rounded-full border border-gray-200 bg-white px-4 py-2 text-xs font-black uppercase tracking-[0.2em] text-gray-400">
                    {{ __('Next') }}
                </span>
            @endif
        </div>

        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-600">
                    {{ __('Showing') }}
                    <span class="font-black text-gray-900">{{ $paginator->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-black text-gray-900">{{ $paginator->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-black text-gray-900">{{ $paginator->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div>
                <span class="isolate inline-flex rounded-full shadow-sm">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}" class="inline-flex items-center rounded-l-full border border-gray-200 bg-white px-3 py-2 text-sm font-black text-gray-400">
                            <span class="sr-only">{{ __('pagination.previous') }}</span>
                            <span aria-hidden="true">&lsaquo;</span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}" class="inline-flex items-center rounded-l-full border border-gray-200 bg-white px-3 py-2 text-sm font-black text-gray-700 transition hover:border-primary hover:bg-primary hover:text-white">
                            <span class="sr-only">{{ __('pagination.previous') }}</span>
                            <span aria-hidden="true">&lsaquo;</span>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true" class="inline-flex items-center border-y border-gray-200 bg-white px-4 py-2 text-sm font-black text-gray-400">{{ $element }}</span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page" class="inline-flex items-center border border-primary bg-primary px-4 py-2 text-sm font-black text-white">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="inline-flex items-center border border-gray-200 bg-white px-4 py-2 text-sm font-black text-gray-700 transition hover:border-primary hover:bg-primary/5 hover:text-primary">{{ $page }}</a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}" class="inline-flex items-center rounded-r-full border border-gray-200 bg-white px-3 py-2 text-sm font-black text-gray-700 transition hover:border-primary hover:bg-primary hover:text-white">
                            <span class="sr-only">{{ __('pagination.next') }}</span>
                            <span aria-hidden="true">&rsaquo;</span>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}" class="inline-flex items-center rounded-r-full border border-gray-200 bg-white px-3 py-2 text-sm font-black text-gray-400">
                            <span class="sr-only">{{ __('pagination.next') }}</span>
                            <span aria-hidden="true">&rsaquo;</span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif