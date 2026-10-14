{{-- resources/views/partials/flash-messages.blade.php
     Success / warning / error messages sent by the server.
     They stay on screen until the user closes them (no timeout), and are announced
     to screen readers. Usage:  @include('partials.flash-messages')  --}}
@foreach([
    'success' => ['alert-success', 'status'],
    'warning' => ['alert-warning', 'alert'],
    'error'   => ['alert-danger',  'alert'],
] as $key => $style)
    @if(session($key))
        <div class="alert {{ $style[0] }} alert-dismissible fade show" role="{{ $style[1] }}">
            {{ session($key) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close message"></button>
        </div>
    @endif
@endforeach