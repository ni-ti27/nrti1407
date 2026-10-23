{{-- ============================================================
     Officer Page Header & Context Banner (Embedded, No White Card)
     Compliant with DBIM Primary Palette & GIGW 3.0 Accessibility
     ============================================================ --}}
<header class="dbim-page-header mb-4" role="region" aria-label="Page Title and Officer Context">
    {{-- Page Heading + Department Title Group --}}
    <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-end border-bottom dbim-header-divider pb-3 mb-3 gap-2">
        <div>
            <h1 class="h2 dbim-text-key fw-bold mb-1">{{ $pageTitle ?? 'View Comments / Reply' }}</h1>
            <p class="dbim-dept-heading mb-0">
                {{ session('faaDeptName') ?? session('department_name') }}
                @if(!in_array(session('role_name'), ['Nodal Officer', 'First Appellete Authority']))
                    @if(session('subDepartmentName') || session('subDepartment_name'))
                        <span class="dbim-dept-sub"> &mdash; {{ session('subDepartmentName') ?? session('subDepartment_name') }}</span>
                    @endif
                    @if(session('officeName') || session('officeDepartment_name'))
                        <span class="dbim-dept-sub"> ({{ session('officeName') ?? session('officeDepartment_name') }})</span>
                    @endif
                @endif
            </p>
        </div>

        {{-- Meta info inline (Username & Role) --}}
        <div class="d-flex align-items-center gap-3 dbim-meta-strip">
            <span class="dbim-meta-item">
                <strong class="dbim-meta-label">Username:</strong>
                <span class="dbim-meta-value">{{ session('username') }}</span>
            </span>
            <span class="dbim-meta-separator" aria-hidden="true">|</span>
            <span class="dbim-meta-item">
                <strong class="dbim-meta-label">Role:</strong>
                <span class="dbim-role-pill">{{ session('role_name') ?? session('roleName') }}</span>
            </span>
        </div>
    </div>
</header>