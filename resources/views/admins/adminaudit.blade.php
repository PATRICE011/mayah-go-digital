@extends('admins.layout')
@section('title', 'Mayah Store - Admin Audit Trail')
@section('content')
@include('admins.adminheader', ['activePage' => 'audit'])

<div class="dashboard-wrapper">
    <div class="container-fluid dashboard-content py-4">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-2">Audit Trail</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="" class="breadcrumb-link">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="#" class="breadcrumb-link">Reports</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    Audit Trail
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div id="last-updated" class="text-muted small">
                        <i class="fa fa-clock"></i> Last updated: {{ now()->format('h:i A, d-m-Y') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Audit Trail Card -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card shadow-sm">
                    <!-- Card Header with Filters -->
                    <div class="card-header bg-white py-3">
                        <div class="row align-items-center">
                            <div class="col-lg-8 col-md-6 mb-2 mb-md-0">
                                <div class="d-flex align-items-center">
                                    <!-- Search and Filter Form -->
                                    <form action="{{ url()->current() }}" method="GET" class="d-flex flex-wrap align-items-center">
                                  
                                        <div class="d-flex flex-wrap">
                                            <!-- Action Filter -->
                                            <select name="action" class="form-control form-control-sm mr-2 mb-2 mb-sm-0" style="width: 120px;">
                                                <option value="">All Actions</option>
                                                <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                                                <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
                                                <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Create</option>
                                                <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Update</option>
                                                <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Delete</option>
                                            </select>

                                            <!-- User Filter -->
                                            <select name="user" class="form-control form-control-sm mr-2 mb-2 mb-sm-0" style="width: 150px;">
                                                <option value="">All Users</option>
                                                @foreach(\App\Models\User::whereIn('role_id', [1, 2])->orderBy('name')->get() as $userOption)
                                                <option value="{{ $userOption->id }}" {{ request('user') == $userOption->id ? 'selected' : '' }}>
                                                    {{ $userOption->name }}
                                                </option>
                                                @endforeach
                                            </select>

                                            <!-- Date Range -->
                                            <input type="date" class="form-control form-control-sm mr-2 mb-2 mb-sm-0" name="start_date"
                                                value="{{ request('start_date') }}" placeholder="Start Date" style="width: 140px;">
                                            <input type="date" class="form-control form-control-sm mr-2 mb-2 mb-sm-0" name="end_date"
                                                value="{{ request('end_date') }}" placeholder="End Date" style="width: 140px;">

                                            <div class="d-flex mb-2 mb-sm-0">
                                                <button type="submit" class="btn btn-sm btn-primary mr-2">
                                                    <i class="fa fa-filter"></i> Filter
                                                </button>
                                                <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fa fa-times"></i> Clear
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body with Audit Table -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="px-4 py-3 border-0" style="text-align: center;">#</th>
                                        <th class="px-4 py-3 border-0">Name</th>
                                        <th class="px-4 py-3 border-0">Role</th>
                                        <th class="px-4 py-3 border-0">Date & Time</th>
                                        <th class="px-4 py-3 border-0" style="min-width: 300px;">Description</th>
                                        <th class="px-4 py-3 border-0 text-center" style="width: 80px;">Details</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($audits as $audit)
                                    <tr>
                                        <td style="text-align: center;">{{ ($audits->currentPage() - 1) *$audits->perPage() + $loop->iteration }}</td>
                                        <td class="px-4 py-3">{{ $audit->user->name ?? 'System' }}</td>
                                        <td class="px-4 py-3">
                                            <span class="badge badge-pill badge-{{ $audit->user->role->name === 'admin' ? 'danger' : 'info' }}">
                                                {{ $audit->user->role->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">{{ $audit->created_at->format('h:i A, d-m-Y') }}</td>
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center">
                                                @php
                                                $actionIcon = 'fa-info-circle';
                                                $actionColor = 'text-info';

                                                if (stripos($audit->action, 'login') !== false) {
                                                $actionIcon = 'fa-sign-in-alt';
                                                $actionColor = 'text-success';
                                                } elseif (stripos($audit->action, 'logout') !== false) {
                                                $actionIcon = 'fa-sign-out-alt';
                                                $actionColor = 'text-secondary';
                                                } elseif (stripos($audit->action, 'add') !== false || stripos($audit->action, 'create') !== false) {
                                                $actionIcon = 'fa-plus-circle';
                                                $actionColor = 'text-primary';
                                                } elseif (stripos($audit->action, 'update') !== false || stripos($audit->action, 'edit') !== false) {
                                                $actionIcon = 'fa-edit';
                                                $actionColor = 'text-warning';
                                                } elseif (stripos($audit->action, 'delete') !== false) {
                                                $actionIcon = 'fa-trash-alt';
                                                $actionColor = 'text-danger';
                                                }
                                                @endphp

                                                <i class="fa {{ $actionIcon }} {{ $actionColor }} mr-2"></i>
                                                <span>{{ ucfirst($audit->action) }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <button class="btn btn-sm btn-outline-secondary view-details" data-id="{{ $audit->id }}">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="fa fa-search fa-2x text-muted mb-3"></i>
                                                <p class="text-muted">No audit logs found.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="card-footer bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="small text-muted">
                                    Showing {{ $audits->firstItem() ?? 0 }} to {{ $audits->lastItem() ?? 0 }} of {{ $audits->total() ?? 0 }} entries
                                </div>
                                <nav aria-label="Audit Trail Pagination">
                                    <ul class="pagination pagination-sm mb-0">
                                        @if ($audits->onFirstPage())
                                        <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                                        @else
                                        <li class="page-item"><a class="page-link" href="{{ url()->current() . '?page=' . $audits->previousPageUrl() }}">&laquo;</a></li>
                                        @endif

                                        @foreach ($audits->getUrlRange(1, $audits->lastPage()) as $page => $url)
                                        <li class="page-item {{ $audits->currentPage() == $page ? 'active' : '' }}">
                                            <a class="page-link" href="{{ url()->current() . '?page=' . $page }}">{{ $page }}</a>
                                        </li>
                                        @endforeach

                                        @if ($audits->hasMorePages())
                                        <li class="page-item"><a class="page-link" href="{{ url()->current() . '?page=' . $audits->nextPageUrl() }}">&raquo;</a></li>
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

<!-- Audit Details Modal -->
<div class="modal fade" id="auditDetailsModal" tabindex="-1" role="dialog" aria-labelledby="auditDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="auditDetailsModalLabel">Audit Log Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="audit-details-content">
                    <!-- Content will be loaded here via AJAX -->
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('assets/js/audit.js') }}?v={{ time() }}"></script>
@endsection
