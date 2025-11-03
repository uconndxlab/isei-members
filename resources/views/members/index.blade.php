@extends('layouts.app')

@section('title', 'Member Directory')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">ISEI Member Directory</h2>
                    <p class="text-muted mb-0">Connect with international social economy researchers</p>
                </div>
                @if (optional(auth()->user())->is_admin)
                    <a href="{{ route('admin.index') }}" class="btn btn-outline-primary">Admin Dashboard</a>
                @endif
            </div>

            <!-- Search and Filter Card -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Search & Filter Members</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('members.index') }}" 
                          hx-get="{{ route('members.index') }}" 
                          hx-target="#results" 
                          hx-swap="innerHTML" 
                          hx-select="#results">
                        @csrf
                        <div class="row g-3">
                            <!-- Name Search -->
                            <div class="col-md-4">
                                <label for="name" class="form-label fw-bold">Name</label>
                                <input type="text" 
                                       name="name" 
                                       id="name"
                                       class="form-control" 
                                       placeholder="Search by first or last name" 
                                       value="{{ request('name') }}">
                            </div>

                            <!-- Country Select -->
                            <div class="col-md-4">
                                <label for="country" class="form-label fw-bold">Country</label>
                                <select name="country" id="country" class="form-select">
                                    <option value="">All Countries</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
                                            {{ $country }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- General Interest Select -->
                            <div class="col-md-4">
                                <label for="gen_int" class="form-label fw-bold">Research Interest</label>
                                <select name="gen_int" id="gen_int" class="form-select">
                                    <option value="">All Research Areas</option>
                                    @foreach ($general_interests as $interest)
                                        <option value="{{ $interest }}" {{ request('gen_int') == $interest ? 'selected' : '' }}>
                                            {{ $interest }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-2">Search Members</button>
                                <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">Clear Filters</a>
                                @if(request()->hasAny(['name', 'country', 'gen_int']))
                                    <span class="ms-3 text-muted">
                                        <small>Filtering results</small>
                                    </span>
                                @endif
                            </div>
                        </div>
                </div>
            </div>

            <!-- Active Filter Indicator -->
            @if(request()->hasAny(['name', 'country', 'gen_int']))
                <div class="filter-indicator mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <strong>Active Filters:</strong>
                            @if(request('name'))
                                <span class="badge bg-primary me-2">Name: "{{ request('name') }}"</span>
                            @endif
                            @if(request('country'))
                                <span class="badge bg-primary me-2">Country: {{ request('country') }}</span>
                            @endif
                            @if(request('gen_int'))
                                <span class="badge bg-primary me-2">Interest: {{ request('gen_int') }}</span>
                            @endif
                        </div>
                        <a href="{{ route('members.index') }}" class="btn btn-sm btn-outline-secondary">Clear All</a>
                    </div>
                </div>
            @endif
                    </form>
                </div>
            </div>

            <!-- Results Card -->
            <div class="card">
                <div class="card-header bg-light">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Directory Results</h5>
                            <small class="text-muted">
                                @if($members->total() == 1)
                                    1 member found
                                @else
                                    {{ number_format($members->total()) }} members found
                                @endif
                            </small>
                        </div>
                        <div class="col-auto">
                            <small class="text-muted">Click email to contact • Showing {{ $members->count() }} of {{ $members->total() }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($members->count() > 0)
                        <div id="results" class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 18%">Name & Degree</th>
                                        <th style="width: 20%">Position</th>
                                        <th style="width: 22%">Organization</th>
                                        <th style="width: 18%">Contact</th>
                                        <th style="width: 22%">Research Interests</th>
                                        @if (optional(auth()->user())->is_admin)
                                            <th style="width: 10%" class="text-end">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($members as $member)
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-dark">
                                                    {{ $member->first_name }} {{ $member->last_name }}
                                                </div>
                                                @if($member->degree)
                                                    <small class="text-muted">{{ $member->degree }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 180px;" title="{{ $member->position }}">
                                                    {{ $member->position ?: '—' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 200px;" title="{{ $member->organization }}">
                                                    {{ $member->organization ?: '—' }}
                                                </div>
                                                @if($member->country)
                                                    <small class="text-muted d-block">{{ $member->country }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($member->email)
                                                    <a href="mailto:{{ $member->email }}" 
                                                       class="text-decoration-none text-primary fw-medium">
                                                        {{ $member->email }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="research-interests">
                                                    @php
                                                        $interests = collect([$member->gen_int1, $member->gen_int2, $member->gen_int3])->filter();
                                                    @endphp
                                                    @if($interests->count() > 0)
                                                        @foreach($interests as $interest)
                                                            <a href="{{ route('members.index', array_merge(request()->query(), ['gen_int' => $interest])) }}" 
                                                               class="badge bg-light text-dark border me-1 mb-1 text-decoration-none interest-badge"
                                                               title="Filter by: {{ $interest }}"
                                                               data-interest="{{ $interest }}">
                                                                {{ $interest }}
                                                            </a>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </div>
                                            </td>
                                            @if (optional(auth()->user())->is_admin)
                                                <td class="text-end">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('admin.members.edit', $member) }}" 
                                                           class="btn btn-outline-primary btn-sm"
                                                           title="Edit Member">
                                                            Edit
                                                        </a>
                                                        <button type="button" 
                                                                class="btn btn-outline-danger btn-sm delete-member" 
                                                                data-member-id="{{ $member->id }}"
                                                                data-member-name="{{ $member->first_name }} {{ $member->last_name }}"
                                                                title="Delete Member">
                                                            Delete
                                                        </button>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($members->hasPages())
                            <div class="card-footer bg-light">
                                {{ $members->appends(request()->query())->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <h5 class="text-muted">No members found</h5>
                            @if(request()->hasAny(['name', 'country', 'gen_int']))
                                <p class="text-muted">Try adjusting your search filters or <a href="{{ route('members.index') }}" class="text-decoration-none">clear all filters</a>.</p>
                            @else
                                <p class="text-muted">The member directory is currently empty.</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if (optional(auth()->user())->is_admin)
<!-- Delete Confirmation Modal (Admin Only) -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="memberName"></strong>?</p>
                <p class="text-muted mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Member</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    @if (optional(auth()->user())->is_admin)
    // Delete confirmation for admin users
    document.querySelectorAll('.delete-member').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const memberId = this.dataset.memberId;
            const memberName = this.dataset.memberName;
            
            document.getElementById('memberName').textContent = memberName;
            document.getElementById('deleteForm').action = `/admin/members/${memberId}`;
            
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        });
    });
    @endif
    
    // Highlight active interest badges
    const currentInterest = new URLSearchParams(window.location.search).get('gen_int');
    if (currentInterest) {
        document.querySelectorAll('.interest-badge').forEach(badge => {
            if (badge.dataset.interest === currentInterest) {
                badge.classList.add('active');
            }
        });
    }
    
    // Add click analytics for badges (optional)
    document.querySelectorAll('.interest-badge').forEach(badge => {
        badge.addEventListener('click', function(e) {
            // Add loading state
            this.style.opacity = '0.7';
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>' + this.dataset.interest;
        });
    });
    
    // Auto-submit search on select changes for better UX
    const selects = document.querySelectorAll('select[name="country"], select[name="gen_int"]');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            // Small delay to allow user to see their selection
            setTimeout(() => {
                this.closest('form').submit();
            }, 100);
        });
    });
    
    // Search input with debounce
    const searchInput = document.querySelector('input[name="name"]');
    if (searchInput) {
        let timeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                if (this.value.length >= 3 || this.value.length === 0) {
                    this.closest('form').submit();
                }
            }, 500);
        });
    }
    
    // Smooth scroll to results when filter changes
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('gen_int') || urlParams.has('country') || urlParams.has('name')) {
        setTimeout(() => {
            document.querySelector('#results')?.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
        }, 100);
    }
});
</script>

<style>
.research-interests .badge {
    font-size: 0.75rem;
    font-weight: normal;
}

.interest-badge {
    cursor: pointer;
    transition: all 0.15s ease-in-out;
}

.interest-badge:hover {
    background-color: #0d6efd !important;
    color: white !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(13, 110, 253, 0.2);
}

.interest-badge.active {
    background-color: #0d6efd !important;
    color: white !important;
    border-color: #0d6efd !important;
}

.table td {
    vertical-align: middle;
    padding: 1rem 0.75rem;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.filter-indicator {
    background-color: #e7f3ff;
    border: 1px solid #b6d7ff;
    border-radius: 4px;
    padding: 0.5rem 0.75rem;
    margin-bottom: 1rem;
}
</style>
@endsection
