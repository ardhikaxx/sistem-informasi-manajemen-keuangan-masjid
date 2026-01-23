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

                <!-- Edit Profil Form -->
                <form id="profilForm">
                    <div class="d-flex gap-3 flex-column flex-md-row">
                        <div class="col-md-6 p-3">
                            <h6 class="fw-bold mb-3 border-bottom pb-2" style="color: var(--primary-color);">
                                <i class="fas fa-user-edit me-2"></i>Informasi Profil
                            </h6>

                            <div class="mb-3">
                                <label for="namaLengkap" class="form-label fw-medium">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="namaLengkap" value="Admin Masjid" required>
                                <div class="form-text">Nama lengkap yang akan ditampilkan di sistem</div>
                            </div>

                            <div class="mb-3">
                                <label for="nomorTelepon" class="form-label fw-medium">
                                    Nomor Telepon <span class="text-danger">*</span>
                                </label>
                                <input type="tel" class="form-control" id="nomorTelepon" value="081234567890" required>
                                <div class="form-text">Nomor telepon aktif untuk kontak</div>
                            </div>
                        </div>

                        <!-- Ubah PIN Section -->
                        <div class="col-md-6 p-3">
                            <h6 class="fw-bold mb-3 border-bottom pb-2" style="color: var(--primary-color);">
                                <i class="fas fa-lock me-2"></i>Keamanan
                            </h6>

                            <div class="mb-3">
                                <label for="pinSekarang" class="form-label fw-medium">
                                    PIN Sekarang <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="pinSekarang"
                                        placeholder="Masukkan PIN saat ini" maxlength="4" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePinSekarang">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">Masukkan PIN 4 digit yang sedang aktif</div>
                            </div>

                            <div class="mb-3">
                                <label for="pinBaru" class="form-label fw-medium">
                                    PIN Baru <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="pinBaru"
                                        placeholder="Masukkan PIN baru" maxlength="4" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePinBaru">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">PIN baru harus terdiri dari 4 digit angka</div>
                            </div>

                            <div class="mb-4">
                                <label for="konfirmasiPin" class="form-label fw-medium">
                                    Konfirmasi PIN Baru <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="konfirmasiPin"
                                        placeholder="Konfirmasi PIN baru" maxlength="4" required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggleKonfirmasiPin">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">Masukkan kembali PIN baru untuk konfirmasi</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                        <button type="reset" class="btn btn-secondary px-4">
                            <i class="fas fa-times me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass-modal">
                <div class="modal-body text-center py-5">
                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-4"
                        style="width: 80px; height: 80px;">
                        <i class="fas fa-check fa-2x text-success"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-3">Perubahan Berhasil Disimpan!</h5>
                    <p class="text-muted mb-4">Profil dan PIN Anda telah diperbarui dengan sukses.</p>
                    <button type="button" class="btn btn-primary px-4" data-bs-dismiss="modal">
                        Tutup
                    </button>
                </div>
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

        .alert-warning {
            border-left: 4px solid var(--warning-color);
        }

        .border-bottom {
            border-color: rgba(var(--primary-rgb), 0.1) !important;
        }

        .border-top {
            border-color: rgba(var(--primary-rgb), 0.1) !important;
        }
    </style>
@endpush

@push('scripts')
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

                button.addEventListener('click', function() {
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
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
            });

            // PIN validation - only allow numbers
            const pinInputs = ['pinSekarang', 'pinBaru', 'konfirmasiPin'];
            pinInputs.forEach(inputId => {
                const input = document.getElementById(inputId);

                input.addEventListener('input', function() {
                    // Remove non-numeric characters
                    this.value = this.value.replace(/\D/g, '');

                    // Limit to 4 digits
                    if (this.value.length > 4) {
                        this.value = this.value.slice(0, 4);
                    }
                });

                input.addEventListener('keypress', function(e) {
                    // Only allow numbers
                    if (!/[0-9]/.test(e.key)) {
                        e.preventDefault();
                    }
                });
            });

            // Form submission
            const profilForm = document.getElementById('profilForm');
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));

            profilForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Get form values
                const namaLengkap = document.getElementById('namaLengkap').value.trim();
                const nomorTelepon = document.getElementById('nomorTelepon').value.trim();
                const pinSekarang = document.getElementById('pinSekarang').value;
                const pinBaru = document.getElementById('pinBaru').value;
                const konfirmasiPin = document.getElementById('konfirmasiPin').value;

                // Validation
                let isValid = true;
                let errorMessage = '';

                // Nama validation
                if (!namaLengkap) {
                    isValid = false;
                    errorMessage = 'Nama lengkap harus diisi';
                }

                // Nomor telepon validation
                const teleponRegex = /^[0-9]{10,13}$/;
                if (!teleponRegex.test(nomorTelepon)) {
                    isValid = false;
                    errorMessage = 'Nomor telepon harus terdiri dari 10-13 digit angka';
                }

                // PIN validation
                if (pinSekarang.length !== 4) {
                    isValid = false;
                    errorMessage = 'PIN saat ini harus 4 digit';
                } else if (pinBaru.length !== 4) {
                    isValid = false;
                    errorMessage = 'PIN baru harus 4 digit';
                } else if (pinBaru !== konfirmasiPin) {
                    isValid = false;
                    errorMessage = 'PIN baru dan konfirmasi PIN tidak cocok';
                }

                if (!isValid) {
                    alert('Error: ' + errorMessage);
                    return;
                }

                // Simulate API call
                console.log('Data yang akan dikirim:');
                console.log('Nama:', namaLengkap);
                console.log('Telepon:', nomorTelepon);
                console.log('PIN Lama:', pinSekarang);
                console.log('PIN Baru:', pinBaru);

                // In a real application, you would send this data to your server
                // using fetch or axios

                // Show success modal
                successModal.show();
            });

            // Reset form confirmation
            profilForm.addEventListener('reset', function(e) {
                if (!confirm('Apakah Anda yakin ingin membatalkan perubahan?')) {
                    e.preventDefault();
                }
            });

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
