@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">{{ isset($member) ? 'Edit Member' : 'Add New Member' }}</h2>
                <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">← Back to Members</a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form action="{{ isset($member) ? route('admin.members.update', $member->id) : route('admin.members.store') }}" method="POST">
                        @csrf
                        @if(isset($member))
                            @method('PUT')
                        @endif

                        <input type="hidden" name="id" value="{{ $member->id ?? '' }}">

                        <!-- Personal Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Personal Information</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label fw-bold">First Name *</label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       name="first_name" 
                                       id="first_name" 
                                       value="{{ old('first_name', $member->first_name ?? '') }}" 
                                       required
                                       autofocus>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label fw-bold">Last Name *</label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       name="last_name" 
                                       id="last_name" 
                                       value="{{ old('last_name', $member->last_name ?? '') }}" 
                                       required>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label for="email" class="form-label fw-bold">Email Address *</label>
                                <input type="email" 
                                       class="form-control form-control-lg" 
                                       name="email" 
                                       id="email" 
                                       value="{{ old('email', $member->email ?? '') }}" 
                                       required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="country" class="form-label fw-bold">Country *</label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       name="country" 
                                       id="country" 
                                       value="{{ old('country', $member->country ?? '') }}" 
                                       required>
                            </div>
                        </div>

                        <!-- Professional Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Professional Information</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="position" class="form-label fw-bold">Position/Title</label>
                                <input type="text" 
                                       class="form-control" 
                                       name="position" 
                                       id="position" 
                                       value="{{ old('position', $member->position ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="degree" class="form-label fw-bold">Degree</label>
                                <input type="text" 
                                       class="form-control" 
                                       name="degree" 
                                       id="degree" 
                                       value="{{ old('degree', $member->degree ?? '') }}">
                            </div>
                            <div class="col-12 mb-3">
                                <label for="organization" class="form-label fw-bold">Organization</label>
                                <textarea class="form-control" 
                                          name="organization" 
                                          id="organization" 
                                          rows="2">{{ old('organization', $member->organization ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Research Interests Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Research Interests</h5>
                                <p class="text-muted small mb-3">Enter up to 3 general research interests</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="gen_int1" class="form-label fw-bold">Interest 1</label>
                                <input type="text" 
                                       class="form-control" 
                                       name="gen_int1" 
                                       id="gen_int1" 
                                       value="{{ old('gen_int1', $member->gen_int1 ?? '') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="gen_int2" class="form-label fw-bold">Interest 2</label>
                                <input type="text" 
                                       class="form-control" 
                                       name="gen_int2" 
                                       id="gen_int2" 
                                       value="{{ old('gen_int2', $member->gen_int2 ?? '') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="gen_int3" class="form-label fw-bold">Interest 3</label>
                                <input type="text" 
                                       class="form-control" 
                                       name="gen_int3" 
                                       id="gen_int3" 
                                       value="{{ old('gen_int3', $member->gen_int3 ?? '') }}">
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="border-top pt-3">
                                    <button type="submit" class="btn btn-primary btn-lg me-3">
                                        {{ isset($member) ? 'Update Member' : 'Create Member' }}
                                    </button>
                                    <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary btn-lg">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-focus on first field and add keyboard shortcuts for efficiency
document.addEventListener('DOMContentLoaded', function() {
    // Ctrl+S to save
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            document.querySelector('form').submit();
        }
    });
    
    // Tab through fields efficiently - already handled by browser but ensure proper tabindex
    const inputs = document.querySelectorAll('input, textarea, select');
    inputs.forEach((input, index) => {
        input.setAttribute('tabindex', index + 1);
    });
});
</script>
@endsection
