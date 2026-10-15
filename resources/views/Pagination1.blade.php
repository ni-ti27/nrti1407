{{-- resources/views/pagination.blade.php
     DBIM / GIGW 3.0 pagination (keyboard + screen reader friendly).
     Use:  {{ $items->appends([...])->onEachSide(1)->links('pagination', ['label' => 'New request applications']) }}

     - Previous / Next: real links (a disabled button on the first / last page)
     - Page numbers: real links, so Tab and Enter work; the current page is not a link (aria-current)
     - Desktop: all page numbers. Mobile: Previous, current page, Next + "Page x of y" text.
     - Icons are inline SVG (no icon font needed). --}}
@if ($paginator->hasPages())
    <nav class="d-flex flex-column align-items-center mt-4" aria-label="{{ $label ?? 'Pagination' }}">
        <ul class="pagination">

            {{-- Previous --}}
            <li>
                @if ($paginator->onFirstPage())
                    <button type="button" class="button-item previous" disabled aria-label="Previous page">
                        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M15 5l-7 7 7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                @else
                    <a class="button-item previous" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous page">
                        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M15 5l-7 7 7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                @endif
            </li>

            {{-- Page numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item d-none d-md-block"><span class="page-link dots" aria-hidden="true">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item">
                                <span class="page-link active" aria-current="page"><span class="visually-hidden">Page </span>{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item d-none d-md-block">
                                <a class="page-link" href="{{ $url }}" aria-label="Page {{ $page }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            <li>
                @if ($paginator->hasMorePages())
                    <a class="button-item next" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next page">
                        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                @else
                    <button type="button" class="button-item next" disabled aria-label="Next page">
                        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                @endif
            </li>
        </ul>

        <p class="mt-2 mb-0 text-center fs-6">
            Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
            <span class="d-none d-md-inline">
                (showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results)
            </span>
        </p>
    </nav>
@endif