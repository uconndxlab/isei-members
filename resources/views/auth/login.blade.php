@extends('layouts.app')

@section('title', 'Login - ISEI Member Directory')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-dark mb-2">Welcome Back</h2>
                        <p class="text-muted">Sign in to access the ISEI Member Directory</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <div class="d-flex align-items-center">
                                <strong>{{ $errors->first() }}</strong>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">Email Address</label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}"
                                   placeholder="Enter your email address"
                                   required 
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold">Password</label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                   placeholder="Enter your password"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="remember" 
                                       id="remember" 
                                       {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-muted" for="remember">
                                    Keep me signed in
                                </label>
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Sign In
                            </button>
                        </div>
                    </form>

                    <div class="text-center">
                        <small class="text-muted">
                            Access restricted to authorized administrators only
                        </small>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <small class="text-muted">
                    <a href="{{ route('members.index') }}" class="text-decoration-none">
                        ← Back to Member Directory
                    </a>
                </small>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus on email field if it's empty, otherwise focus on password
    const emailField = document.getElementById('email');
    const passwordField = document.getElementById('password');
    
    if (emailField.value === '') {
        emailField.focus();
    } else {
        passwordField.focus();
    }
    
    // Enter key handling for smooth login flow
    emailField.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            passwordField.focus();
        }
    });
    
    // Form validation feedback
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Signing In...';
        submitBtn.disabled = true;
    });
});
</script>

<style>
/* Additional styling for the login page */
body {
    background-color: #f8f9fa;
}

.card {
    border-radius: 12px;
}

.form-control-lg {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control-lg:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
}

.btn-primary {
    border-radius: 8px;
    padding: 12px;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.alert {
    border-radius: 8px;
    border: none;
}
</style>
@endsection
