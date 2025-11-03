@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Member Management</h2>
                <a href="{{ route('admin.members.create') }}" class="btn btn-primary btn-lg">Add New Member</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header bg-light">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">All Members ({{ $members->total() }})</h5>
                        </div>
                        <div class="col-auto">
                            <small class="text-muted">Quick actions: Click name to edit, Double-click row to edit</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($members->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 20%">Name</th>
                                        <th style="width: 25%">Email</th>
                                        <th style="width: 15%">Country</th>
                                        <th style="width: 20%">Organization</th>
                                        <th style="width: 15%">Position</th>
                                        <th style="width: 5%" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($members as $member)
                                        <tr class="member-row" data-member-id="{{ $member->id }}" style="cursor: pointer;">
                                            <td>
                                                <div class="fw-bold">
                                                    <a href="{{ route('admin.members.edit', $member) }}" 
                                                       class="text-decoration-none text-dark">
                                                        {{ $member->first_name }} {{ $member->last_name }}
                                                    </a>
                                                </div>
                                                @if($member->degree)
                                                    <small class="text-muted">{{ $member->degree }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="mailto:{{ $member->email }}" class="text-decoration-none">
                                                    {{ $member->email }}
                                                </a>
                                            </td>
                                            <td>{{ $member->country }}</td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 200px;" title="{{ $member->organization }}">
                                                    {{ $member->organization }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 150px;" title="{{ $member->position }}">
                                                    {{ $member->position }}
                                                </div>
                                            </td>
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
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($members->hasPages())
                            <div class="card-footer bg-light">
                                {{ $members->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <h5 class="text-muted">No members found</h5>
                            <p class="text-muted">Get started by adding your first member.</p>
                            <a href="{{ route('admin.members.create') }}" class="btn btn-primary">Add First Member</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Double-click row to edit
    document.querySelectorAll('.member-row').forEach(row => {
        row.addEventListener('dblclick', function(e) {
            if (!e.target.closest('.btn-group')) {
                const memberId = this.dataset.memberId;
                window.location.href = `/admin/members/${memberId}/edit`;
            }
        });
    });

    // Delete confirmation
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

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl+N for new member
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            window.location.href = '{{ route("admin.members.create") }}';
        }
    });
});
</script>
@endsection
