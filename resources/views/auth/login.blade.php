@extends('layouts.auth')

@section('title', 'Login Admin - Sistem Keuangan Masjid')

@section('content')
    <div class="login-container fade-in">
        <div class="login-card">
            <div class="login-card-header">
                <h2><i class="fas fa-user-shield"></i> Login Admin</h2>
                <p>Masuk dengan PIN 4 digit</p>
            </div>

            <div class="login-card-body">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ $errors->first() }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form id="loginForm" method="POST" action="{{ route('auth.login.post') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label text-center mb-2">
                            <i class="fas fa-key me-1"></i> PIN 4 Digit
                        </label>

                        <div class="pin-input-container">
                            <div class="pin-input-wrapper">
                                <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="pin-digit"
                                    data-index="0" autocomplete="off" autofocus required>
                                <div class="pin-dash">-</div>
                                <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="pin-digit"
                                    data-index="1" autocomplete="off" required>
                                <div class="pin-dash">-</div>
                                <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="pin-digit"
                                    data-index="2" autocomplete="off" required>
                                <div class="pin-dash">-</div>
                                <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="pin-digit"
                                    data-index="3" autocomplete="off" required>
                            </div>
                            <input type="hidden" id="pin" name="pin" value="{{ old('pin') }}">
                            @error('pin')
                                <div class="pin-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <p class="pin-hint">
                            <i class="fas fa-info-circle"></i> Masukkan 4 digit PIN Anda
                        </p>
                    </div>

                    <button type="submit" class="btn-login" id="loginButton">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Masuk</span>
                    </button>

                    <div class="login-options">
                        <a class="back-options" href="{{ route('index') }}">
                            <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="login-footer">
            <p>&copy; {{ date('Y') }} Sistem Keuangan Masjid Jami' Al-Muttaqiin</p>
        </div>
    </div>

    @push('styles')
        <style>
            /* Alert Styles */
            .alert {
                border-radius: 10px;
                border: none;
                padding: 15px;
                margin-bottom: 20px;
                animation: slideDown 0.3s ease;
            }

            .alert-danger {
                background-color: #fff5f5;
                color: #e74c3c;
                border-left: 4px solid #e74c3c;
            }

            .alert-success {
                background-color: #f0fff4;
                color: #28a745;
                border-left: 4px solid #28a745;
            }

            .btn-close {
                padding: 1rem;
                margin-top: -0.375rem;
            }

            @keyframes slideDown {
                from {
                    transform: translateY(-10px);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            /* PIN Input Styles */
            .pin-input-container {
                text-align: center;
                margin-bottom: 15px;
            }

            .pin-input-wrapper {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 5px;
                margin-bottom: 10px;
            }

            .pin-digit {
                width: 60px;
                height: 60px;
                text-align: center;
                font-size: 1.8rem;
                font-weight: 700;
                border: 2px solid #e1e8ed;
                border-radius: 12px;
                background-color: white;
                color: var(--text-dark);
                transition: all 0.3s ease;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                caret-color: transparent;
            }

            .pin-digit:focus {
                outline: none;
                border-color: var(--primary-color);
                box-shadow: 0 0 0 3px rgba(29, 138, 78, 0.1);
                background-color: #f8fff8;
                transform: translateY(-2px);
            }

            .pin-digit.active {
                border-color: var(--primary-color);
                background-color: #f8fff8;
            }

            .pin-digit.error {
                border-color: #e74c3c;
                background-color: #fff5f5;
                animation: shake 0.5s ease;
            }

            .pin-dash {
                font-size: 1.5rem;
                font-weight: 600;
                color: var(--text-light);
                margin: 0 5px;
                user-select: none;
            }

            .pin-error {
                color: #e74c3c;
                font-size: 0.9rem;
                margin-top: 8px;
                min-height: 20px;
                text-align: center;
                animation: fadeIn 0.3s ease;
            }

            .pin-hint {
                color: var(--text-light);
                font-size: 0.9rem;
                text-align: center;
                margin-top: 10px;
                margin-bottom: 0;
            }

            .pin-hint i {
                color: var(--primary-color);
                margin-right: 5px;
            }

            /* Button loading state */
            .btn-login.loading {
                opacity: 0.8;
                cursor: not-allowed;
            }

            /* Animation for error */
            @keyframes shake {
                0%, 100% {
                    transform: translateX(0);
                }
                25% {
                    transform: translateX(-5px);
                }
                75% {
                    transform: translateX(5px);
                }
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }
                to {
                    opacity: 1;
                }
            }

            /* Responsive styles for PIN */
            @media (max-width: 768px) {
                .pin-digit {
                    width: 50px;
                    height: 50px;
                    font-size: 1.5rem;
                }

                .pin-dash {
                    font-size: 1.3rem;
                }
            }

            @media (max-width: 480px) {
                .pin-digit {
                    width: 45px;
                    height: 45px;
                    font-size: 1.3rem;
                }

                .pin-dash {
                    font-size: 1.1rem;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const loginForm = document.getElementById('loginForm');
                const loginButton = document.getElementById('loginButton');
                const pinInputs = document.querySelectorAll('.pin-digit');
                const hiddenPinInput = document.getElementById('pin');
                const pinError = document.querySelector('.pin-error');

                // Function to get complete PIN
                function getPIN() {
                    let pin = '';
                    pinInputs.forEach(input => {
                        pin += input.value;
                    });
                    return pin;
                }

                // Function to update hidden input
                function updateHiddenPIN() {
                    hiddenPinInput.value = getPIN();
                }

                // Function to show error
                function showError(message) {
                    if (pinError) {
                        pinError.textContent = message;
                        pinError.style.color = '#e74c3c';
                        pinError.style.opacity = '1';
                    }

                    // Add error class to all inputs
                    pinInputs.forEach(input => {
                        input.classList.add('error');
                    });
                }

                // Function to clear error
                function clearError() {
                    if (pinError) {
                        pinError.textContent = '';
                    }
                    pinInputs.forEach(input => {
                        input.classList.remove('error');
                    });
                }

                // Auto-fill from old input (for validation errors)
                const oldPin = hiddenPinInput.value;
                if (oldPin && oldPin.length === 4) {
                    pinInputs.forEach((input, index) => {
                        if (index < oldPin.length) {
                            input.value = oldPin[index];
                        }
                    });
                }

                // Handle input focus and navigation
                pinInputs.forEach((input, index) => {
                    // Focus on click
                    input.addEventListener('click', function() {
                        this.select();
                        clearError();
                    });

                    // Handle input
                    input.addEventListener('input', function(e) {
                        clearError();

                        // Allow only numbers
                        this.value = this.value.replace(/[^0-9]/g, '');

                        // Remove active class from all
                        pinInputs.forEach(input => {
                            input.classList.remove('active');
                        });

                        // Add active class to current
                        this.classList.add('active');

                        // If a number was entered and not empty
                        if (this.value.length === 1 && this.value.match(/[0-9]/)) {
                            updateHiddenPIN();

                            // Move to next input if available
                            if (index < pinInputs.length - 1) {
                                pinInputs[index + 1].focus();
                                pinInputs[index + 1].select();
                            } else {
                                // If last input, blur
                                this.blur();
                            }
                        }
                    });

                    // Handle backspace
                    input.addEventListener('keydown', function(e) {
                        clearError();

                        if (e.key === 'Backspace') {
                            // Remove active class from all
                            pinInputs.forEach(input => {
                                input.classList.remove('active');
                            });

                            // Add active class to current
                            this.classList.add('active');

                            // If current is empty, go to previous
                            if (this.value === '' && index > 0) {
                                pinInputs[index - 1].focus();
                                pinInputs[index - 1].select();
                            } else {
                                // Clear current and stay
                                this.value = '';
                                updateHiddenPIN();
                            }
                        }

                        // Handle arrow keys
                        if (e.key === 'ArrowLeft' && index > 0) {
                            e.preventDefault();
                            pinInputs[index - 1].focus();
                            pinInputs[index - 1].select();
                        }

                        if (e.key === 'ArrowRight' && index < pinInputs.length - 1) {
                            e.preventDefault();
                            pinInputs[index + 1].focus();
                            pinInputs[index + 1].select();
                        }

                        // Prevent non-numeric characters (except navigation keys)
                        if (!/^[0-9]$|Backspace|Delete|ArrowLeft|ArrowRight|Tab|Enter$/.test(e.key) && !e.ctrlKey && !e.metaKey) {
                            e.preventDefault();
                        }
                    });

                    // Handle paste
                    input.addEventListener('paste', function(e) {
                        e.preventDefault();
                        const pastedData = (e.clipboardData || window.clipboardData).getData('text');
                        const numbers = pastedData.replace(/[^0-9]/g, '');

                        if (numbers.length === 4) {
                            // Fill all inputs with pasted numbers
                            pinInputs.forEach((input, idx) => {
                                if (idx < 4) {
                                    input.value = numbers[idx] || '';
                                }
                            });
                            updateHiddenPIN();

                            // Focus last input
                            pinInputs[3].focus();
                            pinInputs[3].select();
                        } else {
                            showError('PIN harus 4 digit angka');
                        }
                    });
                });

                // Auto-focus first input on page load
                if (pinInputs[0]) {
                    pinInputs[0].focus();
                }

                // Form submission
                loginForm.addEventListener('submit', function(e) {
                    const pin = getPIN();

                    // Validation
                    if (pin.length !== 4) {
                        showError('PIN harus 4 digit angka');
                        pinInputs[0].focus();
                        e.preventDefault();
                        return;
                    }

                    if (!/^\d{4}$/.test(pin)) {
                        showError('PIN hanya boleh berisi angka');
                        pinInputs[0].focus();
                        e.preventDefault();
                        return;
                    }

                    // Show loading state
                    loginButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Memverifikasi PIN...</span>';
                    loginButton.classList.add('loading');
                    loginButton.disabled = true;

                    // Form will submit normally to backend
                });

                // Enter key to submit from any input
                pinInputs.forEach(input => {
                    input.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            loginForm.dispatchEvent(new Event('submit'));
                        }
                    });
                });

                // Auto-select content on focus for better UX
                pinInputs.forEach(input => {
                    input.addEventListener('focus', function() {
                        this.select();
                        clearError();
                    });
                });
            });
        </script>
    @endpush
@endsection