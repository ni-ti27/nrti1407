{{-- ============================================================
     Related Links  (DBIM / GIGW 3.0, keyboard + screen reader friendly)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Search and pagination work like the Application Status page
     (GET ?search=, resources/views/Pagination.blade.php), but the list
     is the array below, so no controller change is needed.
     Styles: dbim.css section 21. Icons: inline SVG, sized and coloured by CSS.

     To add a link, add one line to $allLinks.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Related Links | RTI Online, Government of Goa')

@section('content')
@php
    $allLinks = collect([
        ['name' => 'Govt. of Goa',                       'url' => 'https://www.goa.gov.in/'],
        ['name' => 'National Portal of India',           'url' => 'https://www.india.gov.in/'],
        ['name' => 'Complaint & Second Appeal to SIC',   'url' => 'https://gsic.goa.gov.in/'],
    ]);

    $term = trim((string) request('search'));
    $filtered = $term === ''
        ? $allLinks
        : $allLinks->filter(function ($l) use ($term) {
              return mb_stripos($l['name'], $term) !== false;
          })->values();

    $perPage = 9;
    $page    = \Illuminate\Pagination\Paginator::resolveCurrentPage();
    $relatedLinks = new \Illuminate\Pagination\LengthAwarePaginator(
        $filtered->forPage($page, $perPage)->values(),
        $filtered->count(),
        $perPage,
        $page,
        ['path' => request()->url(), 'query' => request()->query()]
    );
@endphp

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">

            <h1 class="h2 mb-4">Related Links</h1>

            {{-- ===== Search ===== --}}
            <form method="GET" action="{{ url()->current() }}" role="search" aria-label="Search related links"
                  class="dbim-links-search">
                <label for="search" class="dbim-sr-only">Search related links</label>
                <div class="input-group">
                    <button type="submit" class="dbim-links-search__btn" title="Search" aria-label="Search">
                        <svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 72 72" fill="none">
                            <path d="M70.0532 62.1068L51.5696 43.6253C54.8775 38.6639 56.4469 32.8979 56.2215 27.0516C55.6679 12.686 44.31 1.11459 30.2403 0H26.0223C12.1864 1.15827 1.06805 12.1872 0.0649824 26.2569C-0.0223638 27.4856 -0.020955 28.7665 0.0649824 29.998C1.11173 44.8075 13.4599 56.338 28.3285 56.245C33.7355 56.2112 39.0256 54.6401 43.6239 51.5711L62.4668 70.4049C64.0305 71.9676 66.2889 72.3988 68.364 71.628C70.1913 70.9488 71.6113 69.2593 71.9988 67.2232V65.5351C71.7283 64.1937 71.0718 63.1256 70.0518 62.1054L70.0532 62.1068ZM28.1327 53.4198C14.1672 53.4198 2.84597 42.0964 2.84597 28.1281C2.84597 14.1599 14.1686 2.8379 28.1327 2.8379C42.0968 2.8379 53.4194 14.1613 53.4194 28.1296C53.4194 42.0978 42.0982 53.4212 28.1327 53.4212V53.4198ZM45.9189 49.9013L49.9157 45.9094L53.9815 49.9816L49.9847 53.9736L45.9189 49.9013ZM68.3838 68.3223C67.3666 69.3678 65.6014 69.5707 64.4109 68.3815L51.8852 55.8716C53.3321 54.6218 54.6197 53.3339 55.8693 51.8867L68.3485 64.3783C69.484 65.5154 69.4277 67.2472 68.3824 68.3223H68.3838Z" fill="#162F6A"/>
                            <path d="M28.1314 5.64338C15.7169 5.64338 5.6524 15.7099 5.6524 28.1267C5.6524 40.5436 15.7169 50.6101 28.1314 50.6101C40.5458 50.6101 50.6103 40.5436 50.6103 28.1267C50.6103 15.7099 40.5458 5.64338 28.1314 5.64338ZM28.1314 47.7933C17.2723 47.7933 8.4686 38.988 8.4686 28.1267C8.4686 17.2655 17.2708 8.46014 28.1314 8.46014C38.9919 8.46014 47.7941 17.2655 47.7941 28.1267C47.7941 38.988 38.9905 47.7933 28.1314 47.7933Z" fill="#162F6A"/>
                            <path d="M24.0543 12.4281C17.3666 14.4516 12.6542 20.412 12.1498 27.363C12.0892 28.1887 12.7091 28.8073 13.4375 28.8595C14.1546 28.9116 14.8745 28.4508 14.9491 27.5448C15.4253 21.7168 19.3362 16.8541 24.8714 15.1012C25.6462 14.856 25.9857 14.0866 25.811 13.4173C25.6223 12.693 24.8826 12.1787 24.0557 12.4281H24.0543Z" fill="#162F6A"/>
                        </svg>
                    </button>
                    <input type="search" class="form-control" id="search" name="search"
                           value="{{ $term }}" placeholder="Search related links" autocomplete="off">
                </div>
            </form>

            {{-- Tells screen reader users how many links match a search --}}
            @if($term !== '')
                <p class="dbim-sr-only" role="status">
                    {{ $filtered->count() }} {{ $filtered->count() == 1 ? 'link' : 'links' }} found for {{ $term }}.
                </p>
            @endif

            {{-- ===== List ===== --}}
            @if($relatedLinks->count() == 0)
                <div class="alert alert-info" role="status">
                    No related links found for &ldquo;{{ $term }}&rdquo;.
                    <a href="{{ url()->current() }}">Clear the search</a> to see all links.
                </div>
            @else
                <ul class="dbim-linklist">
                    @foreach($relatedLinks as $link)
                        <li class="dbim-linklist__item">
                            <span class="dbim-linklist__name">{{ $link['name'] }}</span>
                            <a class="link-outline-btn text-uppercase w-ft" href="{{ $link['url'] }}"
                               target="_blank" rel="noopener noreferrer" data-external-confirm
                               title="Visit Website to Know More about {{ $link['name'] }}"
                               aria-label="Visit Website to Know More about {{ $link['name'] }} (opens in a new window)">
                                <svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 66 66" fill="none">
                                    <path d="M57.8889 57.8889H8.11111V8.11111H33V1H8.11111C4.16444 1 1 4.2 1 8.11111V57.8889C1 61.8 4.16444 65 8.11111 65H57.8889C61.8 65 65 61.8 65 57.8889V33H57.8889V57.8889ZM40.1111 1V8.11111H52.8756L17.9244 43.0622L22.9378 48.0756L57.8889 13.1244V25.8889H65V1H40.1111Z" fill="#162F6A"/>
                                </svg>
                                Visit Website
                            </a>
                        </li>
                    @endforeach
                </ul>

                @if($relatedLinks->hasPages())
                    {{ $relatedLinks->onEachSide(1)->links('Pagination', ['label' => 'Related links pages']) }}
                @endif
            @endif

        </div>
    </div>
</div>
@endsection

{{-- ============================================================
     Leaving the portal: asks before opening any link marked with
     data-external-confirm. To use it on another link, add that
     attribute to the <a>. Works for mouse, keyboard (Enter) and
     middle click. "Cancel" keeps the user on this page.
     ============================================================ --}}
@push('scripts')
<script>
    (function () {
        var message = 'You are about to leave the RTI Portal and visit an external website. Do you want to continue?';

        function askBeforeLeaving(event) {
            /* auxclick also fires for the right button: only react to the middle button */
            if (event.type === 'auxclick' && event.button !== 1) { return; }

            var link = event.target.closest('a[data-external-confirm]');
            if (!link) { return; }

            var href = link.getAttribute('href');
            if (!href || href === '#') { event.preventDefault(); return; }

            if (!window.confirm(message)) { event.preventDefault(); }
        }

        document.addEventListener('click', askBeforeLeaving);
        document.addEventListener('auxclick', askBeforeLeaving);
    })();
</script>
@endpush