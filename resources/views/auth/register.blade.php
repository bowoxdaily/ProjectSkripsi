<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Karir Alumni</title>
    <link rel="stylesheet" href="{{ asset('admin/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/vertical-layout-light/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('admin/images/favicon.png') }}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css" rel="stylesheet">
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo">
                                <img src="{{ asset('admin/images/logo.svg') }}" alt="logo">
                            </div>
                            <h4>Daftar Akun Siswa</h4>
                            <h6 class="font-weight-light">Mendaftar sangat mudah. Hanya perlu beberapa langkah</h6>

                            <form class="pt-3">
                                @csrf
                                <div class="form-group">
                                    <input type="text" name="username" class="form-control form-control-lg"
                                        id="username" placeholder="Username" required autofocus>
                                    <div class="invalid-feedback" id="username-error"></div>
                                </div>
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control form-control-lg"
                                        id="email" placeholder="Email" required>
                                    <div class="invalid-feedback" id="email-error"></div>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="no_telp" class="form-control form-control-lg"
                                        id="no_telp" placeholder="Nomor Telepon" required>
                                    <div class="invalid-feedback" id="no_telp-error"></div>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control form-control-lg"
                                        id="password" placeholder="Password" required>
                                    <div class="invalid-feedback" id="password-error"></div>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password_confirmation"
                                        class="form-control form-control-lg" id="password_confirmation"
                                        placeholder="Konfirmasi Password" required>
                                </div>
                                <input type="hidden" name="role" value="siswa">

                                <div class="mt-3">
                                    <button type="submit"
                                        class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                                        DAFTAR
                                    </button>
                                </div>
                                <div class="text-center mt-4 font-weight-light">
                                    Sudah memiliki akun? <a href="{{ route('viewlogin') }}"
                                        class="text-primary">Login</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
    <script src="{{ asset('admin/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('admin/js/off-canvas.js') }}"></script>
    <script src="{{ asset('admin/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('admin/js/template.js') }}"></script>
    <script src="{{ asset('admin/js/settings.js') }}"></script>
    <script src="{{ asset('admin/js/todolist.js') }}"></script>

    <!-- Registration Script -->
    <script>
        $(document).ready(function() {
            // Set up CSRF token for AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Handle form submission
            $('form').on('submit', function(e) {
                e.preventDefault();

                // Reset previous error messages
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                // Get form data
                const formData = {
                    username: $('#username').val(),
                    email: $('#email').val(),
                    no_telp: $('#no_telp').val(),
                    password: $('#password').val(),
                    password_confirmation: $('#password_confirmation').val(),
                    role: 'siswa',
                    _token: $('input[name="_token"]').val()
                };

                // Send AJAX request
                $.ajax({
                    url: '/api/register',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Show success message
                        toastr.success('Registrasi berhasil! Silakan login.');

                        // Redirect to login page after a short delay
                        setTimeout(function() {
                            window.location.href = '/login';
                        }, 2000);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            // Handle validation errors
                            const errors = xhr.responseJSON.errors;

                            // Display validation errors
                            Object.keys(errors).forEach(function(key) {
                                $(`#${key}`).addClass('is-invalid');
                                $(`#${key}-error`).text(errors[key][0]);
                            });

                            toastr.error('Terdapat kesalahan pada form registrasi.');
                        } else {
                            // Handle other errors
                            toastr.error('Terjadi kesalahan sistem. Silakan coba lagi nanti.');
                            console.error(xhr.responseJSON);
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>
