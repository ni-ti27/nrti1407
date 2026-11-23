{{-- ============================================================
     Officer Page Header: public authority name, username and role in one strip
     Place directly under the page title, inside the white card.
     Compliant with DBIM Primary Palette & GIGW 3.0 Accessibility
     Styles: dbim.css section 82
     ============================================================ --}}
<dl class="dbim-nr-bar">
    <div class="dbim-nr-bar__item dbim-nr-bar__item--name">
        <dt class="dbim-sr-only">Public authority:</dt>
        <dd>{{ session('officeName') }}</dd>
    </div>
    <div class="dbim-nr-bar__item">
        <dt>Username:</dt>
        <dd>{{ session('username') ?? '' }}</dd>
    </div>
    <div class="dbim-nr-bar__item">
        <dt>Role:</dt>
        <dd>{{ session('role_name') ?? '' }}</dd>
    </div>
</dl>