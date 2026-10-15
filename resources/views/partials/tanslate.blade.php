<div id="google_translate_element" class="notranslate" aria-hidden="true"></div>

{{-- Language pop-up before entering the site (GIGW 5.4.10) --}}
<div class="modal fade notranslate" id="langModal" tabindex="-1" aria-labelledby="langModalTitle"
     data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title h5" id="langModalTitle">Select your language / अपनी भाषा चुनें</h2>
            </div>
            <div class="modal-body d-flex flex-wrap gap-2">
                @foreach(config('languages.supported') as $code => $name)
                    <a href="#" class="download-btn" data-lang="{{ $code }}" lang="{{ $code }}">{{ $name }}</a>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- config for translate.js - define BEFORE translate.js -->
<script>
    window.DBIM_LANG = {
    default: "{{ app()->getLocale() }}",
    supported: {!! json_encode(config('languages.supported') ?? config('languages') ?? []) !!}
    };
</script>

<script src="{{ asset('js/translate.js') }}"></script>