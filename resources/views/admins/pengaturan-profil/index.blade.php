@extends('layouts.app')
@section('title', 'Pengaturan Profil - Keuangan Masjid')
@section('page-title', 'Pengaturan Profil')
@section('content')
    <div class="row mb-5 justify-content-center fade-in-up">
        <div class="col-md-8 col-lg-12">
            <div class="glass-effect p-4">
                <div class="text-center mb-4">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 100px; height: 100px; background-color: rgba(25, 135, 84, 0.2);">
                        <i class="fas fa-user fa-3x" style="color: var(--primary-color);"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Pengaturan Profil Admin</h5>
                    <p class="text-muted mb-0">Kelola informasi profil dan PIN keamanan Anda</p>
                </div>
                <div id="alert-container" class="mb-4"></div>
                <form id="profilForm">
                    @csrf
                    <div class="d-flex gap-3 flex-column flex-md-row">
                        <div class="col-md-6 p-3">
                            <h6 class="fw-bold mb-3 border-bottom pb-2" style="color: var(--primary-color);">
                                <i class="fas fa-user-edit me-2"></i>Informasi Profil
                            </h6>
                            <div class="mb-3">
                                <label for="namaLengkap" class="form-label fw-medium">
                                    Nama Lengkap
                                </label>
                                <input type="text" class="form-control" id="namaLengkap" name="nama_lengkap"
                                    value="{{ $admin->nama_lengkap ?? old('nama_lengkap') }}"
                                    placeholder="Masukkan nama lengkap">
                                <div class="form-text">Kosongkan jika tidak ingin mengubah nama</div>
                                <div class="invalid-feedback" id="namaLengkap-error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="nomorTelepon" class="form-label fw-medium">
                                    Nomor Telepon
                                </label>
                                <input type="tel" class="form-control" id="nomorTelepon" name="nomor_telfon"
                                    value="{{ $admin->nomor_telfon ?? old('nomor_telfon') }}"
                                    placeholder="Masukkan nomor telepon">
                                <div class="form-text">Kosongkan jika tidak ingin mengubah nomor telepon</div>
                                <div class="invalid-feedback" id="nomorTelepon-error"></div>
                            </div>
                        </div>
                        <!-- Ubah PIN Section -->
                        <div class="col-md-6 p-3">
                            <h6 class="fw-bold mb-3 border-bottom pb-2" style="color: var(--primary-color);">
                                <i class="fas fa-lock me-2"></i>Keamanan (Opsional)
                            </h6>
                            <div class="mb-3">
                                <label for="pinSekarang" class="form-label fw-medium">
                                    PIN Sekarang
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="pinSekarang" name="pin_sekarang"
                                        placeholder="Masukkan PIN saat ini" maxlength="4">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePinSekarang">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">Hanya diisi jika ingin mengubah PIN</div>
                                <div class="invalid-feedback" id="pinSekarang-error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="pinBaru" class="form-label fw-medium">
                                    PIN Baru
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="pinBaru" name="pin_baru"
                                        placeholder="Masukkan PIN baru" maxlength="4">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePinBaru">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">Kosongkan jika tidak ingin mengubah PIN</div>
                                <div class="invalid-feedback" id="pinBaru-error"></div>
                            </div>
                            <div class="mb-4">
                                <label for="konfirmasiPin" class="form-label fw-medium">
                                    Konfirmasi PIN Baru
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="konfirmasiPin"
                                        name="pin_baru_confirmation" placeholder="Konfirmasi PIN baru" maxlength="4">
                                    <button class="btn btn-outline-secondary" type="button" id="toggleKonfirmasiPin">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">Masukkan kembali PIN baru untuk konfirmasi</div>
                                <div class="invalid-feedback" id="konfirmasiPin-error"></div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                        <button type="reset" class="btn btn-secondary px-4" id="resetBtn">
                            <i class="fas fa-times me-2"></i>Reset
                        </button>
                        <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        .glass-modal {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(var(--primary-rgb), 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-color));
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.2);
        }

        .alert-info {
            background-color: rgba(13, 110, 253, 0.1);
            border-left: 4px solid var(--primary-color);
        }

        .border-bottom {
            border-color: rgba(var(--primary-rgb), 0.1) !important;
        }

        .border-top {
            border-color: rgba(var(--primary-rgb), 0.1) !important;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

        .invalid-feedback {
            display: block;
        }

        #submitBtn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        ul {
            padding-left: 20px;
        }
    </style>
@endpush
@push('scripts')
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const toggleButtons = {
                'togglePinSekarang': 'pinSekarang',
                'togglePinBaru': 'pinBaru',
                'toggleKonfirmasiPin': 'konfirmasiPin'
            };

            Object.entries(toggleButtons).forEach(([buttonId, inputId]) => {
                const button = document.getElementById(buttonId);
                const input = document.getElementById(inputId);

                if (button && input) {
                    button.addEventListener('click', function() {
                        const type = input.getAttribute('type') === 'password' ? 'text' :
                            'password';
                        input.setAttribute('type', type);
                        const icon = this.querySelector('i');

                        if (type === 'text') {
                            icon.classList.remove('fa-eye');
                            icon.classList.add('fa-eye-slash');
                        } else {
                            icon.classList.remove('fa-eye-slash');
                            icon.classList.add('fa-eye');
                        }
                    });
                }
            });

            // PIN validation - only allow numbers
            const pinInputs = ['pinSekarang', 'pinBaru', 'konfirmasiPin'];

            pinInputs.forEach(inputId => {
                const input = document.getElementById(inputId);

                if (input) {
                    input.addEventListener('input', function() {
                        // Remove non-numeric characters
                        this.value = this.value.replace(/\D/g, '');

                        // Limit to 4 digits
                        if (this.value.length > 4) {
                            this.value = this.value.slice(0, 4);
                        }

                        // Clear validation
                        clearValidation(this);
                    });

                    input.addEventListener('keypress', function(e) {
                        // Only allow numbers
                        if (!/[0-9]/.test(e.key)) {
                            e.preventDefault();
                        }
                    });
                }
            });

            // Clear validation on input
            document.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', function() {
                    clearValidation(this);
                });
            });

            function clearValidation(input) {
                input.classList.remove('is-invalid');
                const errorElement = document.getElementById(`${input.id}-error`);
                if (errorElement) {
                    errorElement.textContent = '';
                }
            }

            // Form submission
            const profilForm = document.getElementById('profilForm');
            const submitBtn = document.getElementById('submitBtn');
            const resetBtn = document.getElementById('resetBtn');

            if (profilForm && submitBtn) {
                profilForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    // Check if there's any change
                    const originalValues = {
                        nama_lengkap: '{{ $admin->nama_lengkap ?? '' }}',
                        nomor_telfon: '{{ $admin->nomor_telfon ?? '' }}'
                    };

                    const currentValues = {
                        nama_lengkap: document.getElementById('namaLengkap').value.trim(),
                        nomor_telfon: document.getElementById('nomorTelepon').value.trim(),
                        pin_baru: document.getElementById('pinBaru').value.trim(),
                        pin_baru_confirmation: document.getElementById('konfirmasiPin').value
                        .trim(),
                        pin_sekarang: document.getElementById('pinSekarang').value.trim()
                    };

                    // Check if PIN baru diisi tanpa PIN sekarang
                    if (currentValues.pin_baru && !currentValues.pin_sekarang) {
                        document.getElementById('pinSekarang').classList.add('is-invalid');
                        document.getElementById('pinSekarang-error').textContent =
                            'PIN saat ini wajib diisi jika ingin mengubah PIN.';
                        showErrorAlert('Harap isi PIN saat ini jika ingin mengubah PIN.');
                        return;
                    }

                    // Check if PIN baru diisi tanpa konfirmasi
                    if (currentValues.pin_baru && !currentValues.pin_baru_confirmation) {
                        document.getElementById('konfirmasiPin').classList.add('is-invalid');
                        document.getElementById('konfirmasiPin-error').textContent =
                            'Harap konfirmasi PIN baru.';
                        showErrorAlert('Harap konfirmasi PIN baru.');
                        return;
                    }

                    // Check if there's any change at all
                    const hasNameChange = currentValues.nama_lengkap &&
                        currentValues.nama_lengkap !== originalValues.nama_lengkap;
                    const hasPhoneChange = currentValues.nomor_telfon &&
                        currentValues.nomor_telfon !== originalValues.nomor_telfon;
                    const hasPinChange = currentValues.pin_baru && currentValues.pin_sekarang;

                    if (!hasNameChange && !hasPhoneChange && !hasPinChange) {
                        showErrorAlert(
                            'Tidak ada perubahan data. Silakan isi setidaknya satu field yang ingin diubah.'
                            );
                        return;
                    }

                    // Disable submit button
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';

                    // Clear previous alerts
                    clearAlerts();

                    // Clear validation
                    document.querySelectorAll('.is-invalid').forEach(el => {
                        el.classList.remove('is-invalid');
                    });

                    document.querySelectorAll('.invalid-feedback').forEach(el => {
                        el.textContent = '';
                    });

                    try {
                        // Collect form data
                        const formData = new FormData(this);

                        // Remove empty fields
                        for (let [key, value] of formData.entries()) {
                            if (!value.trim()) {
                                formData.delete(key);
                            }
                        }

                        // Send request
                        const response = await fetch('{{ route('admins.pengaturan-profil.update') }}', {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: formData
                        });

                        const data = await response.json();

                        if (response.ok && data.success) {
                            // Success
                            showSuccessAlert(data.message);

                            // Update form values with new data
                            if (data.admin) {
                                document.getElementById('namaLengkap').value = data.admin
                                    .nama_lengkap || '';
                                document.getElementById('nomorTelepon').value = data.admin
                                    .nomor_telfon || '';
                            }

                            // Clear PIN fields
                            document.getElementById('pinSekarang').value = '';
                            document.getElementById('pinBaru').value = '';
                            document.getElementById('konfirmasiPin').value = '';

                            // Update original values
                            originalValues.nama_lengkap = data.admin?.nama_lengkap || originalValues
                                .nama_lengkap;
                            originalValues.nomor_telfon = data.admin?.nomor_telfon || originalValues
                                .nomor_telfon;
                        } else {
                            // Validation errors
                            if (data.errors) {
                                showValidationErrors(data.errors);
                                showErrorAlert('Terdapat kesalahan dalam pengisian form.');
                            } else {
                                showErrorAlert(data.message || 'Terjadi kesalahan.');
                            }
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showErrorAlert('Terjadi kesalahan jaringan. Silakan coba lagi.');
                    } finally {
                        // Re-enable submit button
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Simpan Perubahan';
                    }
                });
            }

            // Reset form
            if (resetBtn) {
                resetBtn.addEventListener('click', function() {
                    if (confirm('Apakah Anda yakin ingin mereset form?')) {
                        // Reset to original values
                        document.getElementById('namaLengkap').value = '{{ $admin->nama_lengkap ?? '' }}';
                        document.getElementById('nomorTelepon').value = '{{ $admin->nomor_telfon ?? '' }}';
                        document.getElementById('pinSekarang').value = '';
                        document.getElementById('pinBaru').value = '';
                        document.getElementById('konfirmasiPin').value = '';

                        // Clear validation
                        clearAlerts();
                        document.querySelectorAll('.is-invalid').forEach(el => {
                            el.classList.remove('is-invalid');
                        });
                        document.querySelectorAll('.invalid-feedback').forEach(el => {
                            el.textContent = '';
                        });
                    }
                });
            }

            // Show SweetAlert success
            function showSuccessAlert(message) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: message,
                    confirmButtonColor: '#198754',
                    confirmButtonText: 'OK',
                    timer: 3000,
                    timerProgressBar: true
                });
            }

            // Show SweetAlert error
            function showErrorAlert(message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: message,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'OK'
                });
            }

            // Show validation errors
            function showValidationErrors(errors) {
                Object.keys(errors).forEach(field => {
                    const input = document.querySelector(`[name="${field}"]`);

                    if (input) {
                        input.classList.add('is-invalid');
                        const errorElement = document.getElementById(`${input.id}-error`) ||
                            document.getElementById(`${field}-error`);

                        if (errorElement) {
                            errorElement.textContent = errors[field][0];
                        }
                    }
                });
            }

            // Clear alerts
            function clearAlerts() {
                const alertContainer = document.getElementById('alert-container');
                if (alertContainer) {
                    alertContainer.innerHTML = '';
                }
            }

            // Add animation
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.fade-in-up').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(el);
            });
        });
    </script>
@endpush
