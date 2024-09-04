<!doctype html>

<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="dark" data-bs-theme="dark" data-theme-colors="default" >

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> @yield('title') </title>
    <meta name="Description" content="Huma Ride">


    @include('includes.style')
    @include('includes.dtstyle')
    @yield('custom-style')

</head>

<body>

<!-- Start Switcher -->

<div class="layout-wrapper">


    <!-- app-header -->
    @include('includes.header')
    <!-- /app-header -->
    <!-- Start::app-sidebar -->
    @include('includes.sidebar')
    <!-- End::app-sidebar -->

    <div class="main-content">
        <div class="page-content">
            <!-- Start::app-content -->
            @yield('content')

            <!-- Footer Start -->
            @include('includes.footer')
            <!-- End::app-content -->
        </div>
    </div>
    <!-- end main content-->
</div>
<!-- END layout-wrapper -->


@include('includes.script')
@include('includes.dtscript')

@yield('custom-script')

</body>

</html>
