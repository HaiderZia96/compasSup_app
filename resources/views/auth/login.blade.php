<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">
<head>
    <meta charset="utf-8" />
    <title>Sign In | Velzon - Admin & Dashboard Template</title>
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
<div class="auth-page-wrapper pt-5">
    <!-- auth page bg -->
    <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
        <div class="bg-overlay"></div>
        <div class="shape">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
                <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
            </svg>
        </div>
    </div>

    <!-- auth page content -->
    <div class="auth-page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mt-sm-5 mb-4 text-white-50">
                        {{--                        <div>--}}
                        {{--                            <a href="{{ url('/') }}" class="d-inline-block auth-logo">--}}
                        {{--                                <img src="{{ asset('admin/images/logo-light.png') }}" alt="" height="20">--}}
                        {{--                            </a>--}}
                        {{--                        </div>--}}
                        <p class="mt-3 fs-15 fw-medium">Compas Sup</p>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card mt-4 card-bg-fill">
                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                <h5 class="text-primary">Welcome Back !</h5>
                                <p class="text-muted">Sign in to continue to Compas Sup.</p>
                            </div>
                            <div class="p-2 mt-4">
                                {{--                                <x-guest-layout>--}}
                                <!-- Session Status -->
                                <x-auth-session-status class="mb-4" :status="session('status')" />

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <!-- Username -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Username</label>
                                        <input type="text" id="email" name="email" class="form-control" placeholder="Enter username" value="{{ old('email') }}" required autofocus autocomplete="username">
                                        @error('email')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-3">
                                        <div class="float-end">
                                            @if (Route::has('password.request'))
                                                <a href="{{ route('password.request') }}" class="text-muted">Forgot password?</a>
                                            @endif
                                        </div>
                                        <label for="password-input" class="form-label">Password</label>
                                        <div class="position-relative">
                                            <input type="password" id="password-input" name="password" class="form-control pe-5" placeholder="Enter password" required autocomplete="current-password">
                                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                        </div>
                                        @error('password')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Remember Me -->
                                    <div class="form-check">
                                        <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                                        <label for="remember_me" class="form-check-label">Remember me</label>
                                    </div>

                                    <div class="mt-4">
                                        <button class="btn btn-success w-100" type="submit">Sign In</button>
                                    </div>

                                    {{--                                        <div class="mt-4 text-center">--}}
                                    {{--                                            <div class="signin-other-title">--}}
                                    {{--                                                <h5 class="fs-13 mb-4 title">Sign In with</h5>--}}
                                    {{--                                            </div>--}}
                                    {{--                                            <div>--}}
                                    {{--                                                <button type="button" class="btn btn-primary btn-icon waves-effect waves-light"><i class="ri-facebook-fill fs-16"></i></button>--}}
                                    {{--                                                <button type="button" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-google-fill fs-16"></i></button>--}}
                                    {{--                                                <button type="button" class="btn btn-dark btn-icon waves-effect waves-light"><i class="ri-github-fill fs-16"></i></button>--}}
                                    {{--                                                <button type="button" class="btn btn-info btn-icon waves-effect waves-light"><i class="ri-twitter-fill fs-16"></i></button>--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </div>--}}
                                </form>
                                {{--                                </x-guest-layout>--}}
                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->

                    {{--                    <div class="mt-4 text-center">--}}
                    {{--                        <p class="mb-0">Don't have an account? <a href="{{ route('register') }}" class="fw-semibold text-primary text-decoration-underline">Signup</a></p>--}}
                    {{--                    </div>--}}
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end auth page content -->

    <!-- footer -->
    {{--    <footer class="footer">--}}
    {{--        <div class="container">--}}
    {{--            <div class="row">--}}
    {{--                <div class="col-lg-12">--}}
    {{--                    <div class="text-center">--}}
    {{--                        <p class="mb-0 text-muted">&copy;--}}
    {{--                            <script>document.write(new Date().getFullYear())</script> Velzon. Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesbrand--}}
    {{--                        </p>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </footer>--}}
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
<!-- particles js -->
<script src="{{ asset('admin/libs/particles.js/particles.js') }}"></script>
<!-- particles app js -->
<script src="{{ asset('admin/js/pages/particles.app.js') }}"></script>
<!-- password-addon init -->
<script src="{{ asset('admin/js/pages/password-addon.init.js') }}"></script>
</body>
</html>
