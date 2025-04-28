{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('admin/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/vendor.bundle.base.css') }}">
    <!-- endinject -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('admin/css/vertical-layout-light/style.css') }}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('admin/images/favicon.png') }}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
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
                            <h4>Hello! let's get started</h4>
                            <h6 class="font-weight-light">Sign in to continue.</h6>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form class="pt-3" id="login-form" novalidate>
                                @csrf
                                <div class="form-group">
                                    <input type="email" name="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        id="email" placeholder="Email" value="{{ old('email') }}" required
                                        autofocus>
                                    <div class="invalid-feedback" id="email-error"></div>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        id="password" placeholder="Password" required>
                                    <div class="invalid-feedback" id="password-error"></div>
                                </div>
                                <div class="mt-3">
                                    <button type="submit"
                                        class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN
                                        IN</button>
                                </div>
                                <div class="my-2 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <label class="form-check-label text-muted">
                                            <input type="checkbox" name="remember" class="form-check-input"
                                                {{ old('remember') ? 'checked' : '' }}>
                                            Keep me signed in
                                        </label>
                                    </div>
                                    @if (Route::has('password.request'))
                                        <a href="#" class="auth-link text-black">Forgot
                                            password?</a>
                                    @endif
                                </div>

                                @if (Route::has('login.facebook'))
                                    <div class="mb-2">
                                        <a href="#" class="btn btn-block btn-facebook auth-form-btn">
                                            <i class="ti-facebook mr-2"></i>Connect using facebook
                                        </a>
                                    </div>
                                @endif

                                <div class="text-center mt-4 font-weight-light">
                                    Don't have an account? <a href="{{ route('viewregister') }}"
                                        class="text-primary">Create</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>


    </div>
    <script src="{{ asset('admin/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- inject:js -->
    <script src="{{ asset('admin/js/off-canvas.js') }}"></script>
    <script src="{{ asset('admin/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('admin/js/template.js') }}"></script>
    <script src="{{ asset('admin/js/settings.js') }}"></script>
    <script src="{{ asset('admin/js/todolist.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    

    <!-- Login Script -->
    <script>
        $(document).ready(function() {
            // Set up CSRF token for AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Configure toastr
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "3000"
            };

            // Handle form submission
            $('#login-form').on('submit', function(e) {
                e.preventDefault();

                // Reset previous error messages
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                // Get form data
                const formData = {
                    email: $('#email').val(),
                    password: $('#password').val(),
                    remember: $('input[name="remember"]').is(':checked') ? 1 : 0,
                    _token: $('input[name="_token"]').val()
                };

                // Show loading state
                const submitBtn = $('.auth-form-btn');
                const originalText = submitBtn.text();
                submitBtn.prop('disabled', true).text('Signing in...');

                // Send AJAX request
                $.ajax({
                    url: '/api/login',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Show success message
                        toastr.success('Login successful!');

                        // Store token in localStorage if needed
                        if (response.data && response.data.token) {
                            localStorage.setItem('auth_token', response.data.token);
                        }

                        // Redirect based on role
                        if (response.data && response.data.redirect_to) {
                            window.location.href = response.data.redirect_to;
                        } else {
                            window.location.href = '/'; // Default redirect
                        }
                    },
                    error: function(xhr) {
                        // Reset button
                        submitBtn.prop('disabled', false).text(originalText);

                        if (xhr.status === 422) {
                            // Handle validation errors
                            const errors = xhr.responseJSON.errors;

                            // Display validation errors
                            Object.keys(errors).forEach(function(key) {
                                $(`#${key}`).addClass('is-invalid');
                                $(`#${key}-error`).text(errors[key][0]);
                            });

                            toastr.error('Please check your input.');
                        } else if (xhr.status === 401) {
                            // Handle invalid credentials
                            toastr.error('Invalid email or password.');
                        } else {
                            // Handle other errors
                            toastr.error('An error occurred. Please try again later.');
                            console.error(xhr.responseJSON);
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>
