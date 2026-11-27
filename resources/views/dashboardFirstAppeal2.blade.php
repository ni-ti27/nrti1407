@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $reqType = $req_type ?? '';
    $pageTitle = match (true) {
        $reqType === '{in_process_FA}' => 'In Process First Appeal Request',
        $reqType === '{closed_FA}'     => 'Closed First Appeal Request',
        default                        => 'New First Appeal Request',
    };
    $isClosed = $reqType === '{closed_FA}';
    $isAdmin  = session('role') == 1;
    $showSubDept = !in_array(session('role_name'), ['Nodal Officer', 'First Appellete Authority']);
    $serial = $firstAppealData->firstItem();
@endphp

<main id="main-content" class="container my-4" tabindex="-1">
    <div class="dbim-form-card p-4">

        {{-- Officer details --}}
        <div class="text-center mb-3">
            <p class="h4 mb-1">{{ session('faaDeptName') }}</p>
            @if($showSubDept)
                <p class="h4 mb-1">{{ session('subDepartmentName') }}</p>
                <p class="h4 mb-1">{{ session('officeName') }}</p>
            @endif
        </div>

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <p class="h5 mb-0">Username: {{ session('username') }}</p>
            <p class="h5 mb-0">Role: {{ session('role_name') }}</p>
        </div>

        <hr>

        <h1 class="h5 text-center mb-4">{{ $pageTitle }}</h1>

        @include('partials.flash-messages')

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <caption class="visually-hidden">{{ $pageTitle }}</caption>
                <thead class="table-light">
                    <tr>
                        <th scope="col">Sr. No</th>
                        <th scope="col">Registration Number</th>
                        <th scope="col">Name</th>
                        <th scope="col">e-Filing Date</th>
                        <th scope="col">{{ $isClosed ? 'Action (in Days)' : 'Pending Since (in Days)' }}</th>
                        @if($isAdmin)
                            <th scope="col">Assigned to (username)</th>
                        @endif
                        @if($isClosed)
                            <th scope="col">Last Action Date</th>
                        @endif
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($firstAppealData as $request)
                        @php
                            $createdAt  = \Carbon\Carbon::parse($request->created_at);
                            $daysPassed = $createdAt->diffInDays($isClosed ? $request->updated_at : now());
                        @endphp
                        <tr>
                            <td>{{ $serial++ }}</td>
                            <td>
                                <a href="{{ route('detailedFARequest', ['id' => $request->id]) }}"
                                   aria-label="View first appeal {{ $request->fa_reg_no }}">{{ $request->fa_reg_no }}</a>
                            </td>
                            <td>{{ $request->name }}</td>
                            <td>{{ $request->created_at->format('d-m-Y H:i') }}</td>
                            <td>{{ $daysPassed }}</td>
                            @if($isAdmin)
                                <td>{{ $request->username }}</td>
                            @endif
                            @if($isClosed)
                                <td>{{ $request->updated_at->format('d-m-Y H:i') }}</td>
                            @endif
                            <td>{{ $request->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 6 + ($isAdmin ? 1 : 0) + ($isClosed ? 1 : 0) - 1 }}" class="text-center">
                                No requests found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <nav aria-label="First appeal requests pagination" class="d-flex justify-content-center mt-3">
            {{ $firstAppealData->links() }}
        </nav>
    </div>
</main>
@endsection

@push('scripts')
<script>
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => el.style.display = 'none');
    }, 10000);
</script>
@endpush