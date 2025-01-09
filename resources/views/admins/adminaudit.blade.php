@extends('admins.layout')
@section('title', 'Mayah Store - Admin Audit Trail')
@section('content')
@include('admins.adminheader', ['activePage' => 'audit'])

<div class="dashboard-wrapper">
    <div class="container-fluid  dashboard-content">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h3 class="mb-2">Audit Trail</h3>

                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#" class="breadcrumb-link">Dashboard</a>
                                </li>

                                <li class="breadcrumb-item">
                                    <a href="#" class="breadcrumb-link">Reports</a>
                                </li>

                                <li class="breadcrumb-item">
                                    <a href="#" class="breadcrumb-link">Audit Trail</a>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-end align-items-center">
                        <button id="refreshAuditListBtn" class="btn btn-sm btn-primary mr-2">
                            <i class="fa fa-sync-alt"></i> Refresh List
                        </button>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr class="border-0">
                                        <th class="border-0">Name</th>
                                        <th class="border-0">Role</th>
                                        <th class="border-0">Date & Time</th>
                                        <th class="border-0" style="max-width: 300px;">Description</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($audits as $audit)
                                    <tr>
                                        <td>{{ $audit->user->name ?? 'System' }}</td>
                                        <td>{{ $audit->user->role->name ?? 'N/A' }}</td>
                                        <td>{{ $audit->created_at->format('h:i A, d-m-Y') }}</td>
                                        <td class="text-truncate" style="max-width: 300px;" title="{{ ucfirst($audit->action) }}">
                                            {{ ucfirst($audit->action) }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No audit logs found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Links -->
                        <div class="card-footer">
                            <div class="d-flex justify-content-end">
                                <nav aria-label="Audit Trail Pagination">
                                    <ul class="pagination justify-content-end">
                                        @if ($audits->onFirstPage())
                                        <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                                        @else
                                        <li class="page-item"><a class="page-link" href="{{ $audits->previousPageUrl() }}">&laquo;</a></li>
                                        @endif

                                        @foreach ($audits->getUrlRange(1, $audits->lastPage()) as $page => $url)
                                        <li class="page-item {{ $audits->currentPage() == $page ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                        @endforeach

                                        @if ($audits->hasMorePages())
                                        <li class="page-item"><a class="page-link" href="{{ $audits->nextPageUrl() }}">&raquo;</a></li>
                                        @else
                                        <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                                        @endif
                                    </ul>
                                </nav>

                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')

<script src="{{ asset('assets/js/audit.js')  }}?v={{ time() }}"></script>
@endsection
@endsection