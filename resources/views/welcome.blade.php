<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title>Sign Up | Velzon - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('admin/images/favicon.ico') }}">
    <!-- Layout config Js -->
    <script src="{{ asset('admin/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('admin/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('admin/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Custom Css-->
    <link href="{{ asset('admin/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
</head>

<body>

<!-- auth-page wrapper -->
<div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
    <div class="bg-overlay"></div>
    <!-- auth-page content -->
    <div class="auth-page-content overflow-hidden pt-lg-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card overflow-hidden m-0 card-bg-fill galaxy-border-none">
                        <div class="row justify-content-center g-0">
                            <div class="col-lg-6">
                                <div class="p-lg-5 p-4 auth-one-bg h-100">
                                    <div class="bg-overlay"></div>
                                    <div class="position-relative h-100 d-flex flex-column">
                                        <div class="mb-4">
                                            <a href="index.html" class="d-block">
                                                <img src="assets/images/logo-light.png" alt="" height="18">
                                            </a>
                                        </div>
                                        <div class="mt-auto">
                                            <div class="mb-3">
                                                <i class="ri-double-quotes-l display-4 text-success"></i>
                                            </div>
                                            <div id="qoutescarouselIndicators" class="carousel slide" data-bs-ride="carousel">
                                                <div class="carousel-indicators">
                                                    <button type="button" data-bs-target="#qoutescarouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                                    <button type="button" data-bs-target="#qoutescarouselIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                                    <button type="button" data-bs-target="#qoutescarouselIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                                </div>
                                                <div class="carousel-inner text-center text-white-50 pb-5">
                                                    <div class="carousel-item active">
                                                        <p class="fs-15 fst-italic">" Great! Clean code, clean design, easy for customization. Thanks very much! "</p>
                                                    </div>
                                                    <div class="carousel-item">
                                                        <p class="fs-15 fst-italic">" The theme is really great with an amazing customer support."</p>
                                                    </div>
                                                    <div class="carousel-item">
                                                        <p class="fs-15 fst-italic">" Great! Clean code, clean design, easy for customization. Thanks very much! "</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="p-lg-5 p-4">
                                    <h5 class="text-primary">SignUp</h5>
                                    <p class="text-muted">Compas Sup</p>

                                    <!-- Blade Form Integration -->
                                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                                        @csrf

                                        <!-- First Name -->
                                        <div class="mb-3">
                                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input id="first_name" class="form-control" type="text" name="first_name" value="{{ old('first_name') }}" required autofocus />
                                            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                                        </div>

                                        <!-- Last Name -->
                                        <div class="mb-3">
                                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input id="last_name" class="form-control" type="text" name="last_name" value="{{ old('last_name') }}" required autofocus />
                                            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                                        </div>

                                        <!-- Email Address -->
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required />
                                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                        </div>

                                        <!-- Password -->
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                            <div class="position-relative">
                                                <input id="password" class="form-control" type="password" name="password" required />
                                                <div class="invalid-feedback">
                                                    Please enter a password.
                                                </div>
                                            </div>
                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required />
                                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                        </div>

                                        <!-- Profile Image -->
                                        <div class="mb-3">
                                            <label for="profile_image" class="form-label">Profile Image</label>
                                            <input id="profile_image" class="form-control" type="file" name="profile_image" />
                                            <x-input-error :messages="$errors->get('profile_image')" class="mt-2" />
                                        </div>

                                        <!-- User Role -->
                                        <div class="mb-3">
                                            <label for="user_role" class="form-label">User Role</label>
                                            <select id="user_role" class="form-select" name="user_role">
                                                <option value="NA">Not Admin</option>
                                                <option value="A">Admin</option>
                                            </select>
                                            <x-input-error :messages="$errors->get('user_role')" class="mt-2" />
                                        </div>

                                        <div class="mt-4">
                                            <button class="btn btn-success w-100" type="submit">Sign Up</button>
                                        </div>

                                        <div class="mt-4 text-center">
                                            <p class="mb-0">Already have an account? <a href="{{ route('login') }}" class="fw-semibold text-primary text-decoration-underline"> Sign in</a></p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card -->
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end auth page content -->

    <!-- footer -->
    <footer class="footer galaxy-border-none">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <p class="mb-0">&copy;
                            <script>document.write(new Date().getFullYear())</script> Velzon. Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesbrand
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- end Footer -->
</div>
<!-- end auth-page-wrapper -->

<!-- JAVASCRIPT -->
<script src="{{ asset('admin/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('admin/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('admin/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('admin/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ asset('admin/js/plugins.js') }}"></script>
<script src="{{ asset('admin/js/pages/form-validation.init.js') }}"></script>
<!-- password create init -->
<script src="{{ asset('admin/js/pages/passowrd-create.init.js') }}"></script>

</body>
</html>
