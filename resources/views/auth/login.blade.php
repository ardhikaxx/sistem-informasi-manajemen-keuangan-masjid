@extends('layouts.auth')

@section('title', 'Login Admin - Sistem Keuangan Masjid')

@section('content')
<div class="login-container fade-in">
    <div class="login-card">
        <div class="login-card-header">
            <h2><i class="fas fa-user-shield"></i> Login Admin</h2>
            <p>Masuk untuk mengelola keuangan masjid</p>
        </div>

        <div class="login-card-body">
            <form id="loginForm" method="POST" action="">
                @csrf
                
                <div class="form-group">
                    <label for="phone" class="form-label">
                        <i class="fas fa-phone-alt me-1"></i> Nomor Telepon
                    </label>
                    <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            class="form-control @error('phone') is-invalid @enderror" 
                            placeholder="Contoh: 081234567890" 
                            value="{{ old('phone') }}"
                            required
                            autofocus
                            autocomplete="tel"
                        >
                </div>

                <button type="submit" class="btn-login" id="loginButton">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Masuk</span>
                </button>

                <div class="login-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Ingat saya</label>
                    </div>
                    <a href="#" class="forgot-password">
                        <i class="fas fa-question-circle"></i> Lupa akses?
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="login-footer">
        <p>
            <a href="{{ route('index') }}">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </p>
        <p>&copy; {{ date('Y') }} Sistem Keuangan Masjid Jami' Al-Muttaqiin</p>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.getElementById('loginForm');
        const loginButton = document.getElementById('loginButton');
        const phoneInput = document.getElementById('phone');

        // Format phone number input
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                if (value.startsWith('0')) {
                    value = '62' + value.substring(1);
                }
                if (!value.startsWith('62')) {
                    value = '62' + value;
                }
            }
            e.target.value = value;
        });

        // Phone validation
        phoneInput.addEventListener('blur', function(e) {
            const value = e.target.value.replace(/\D/g, '');
            if (value.length < 10) {
                showError('Nomor telepon terlalu pendek');
            } else if (value.length > 15) {
                showError('Nomor telepon terlalu panjang');
            }
        });

        // Form submission
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const phone = phoneInput.value.replace(/\D/g, '');
            
            if (!phone) {
                showError('Nomor telepon harus diisi');
                phoneInput.focus();
                return;
            }
            
            if (phone.length < 10) {
                showError('Nomor telepon terlalu pendek');
                phoneInput.focus();
                return;
            }
            
            // Show loading state
            loginButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Memproses...</span>';
            loginButton.classList.add('loading');
            loginButton.disabled = true;
            
            // Simulate API call (replace with actual fetch)
            setTimeout(() => {
                // For demo purposes, we'll just submit the form
                // In real application, remove this timeout and uncomment the next line
                // loginForm.submit();
                
                // Demo success
                alert('Login berhasil! Mengarahkan ke dashboard...');
                window.location.href = '/dashboard';
            }, 1500);
        });

        // Enter key to submit
        phoneInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                loginForm.dispatchEvent(new Event('submit'));
            }
        });
    });
</script>
@endpush
@endsection