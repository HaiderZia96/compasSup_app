<!doctype html>
<html lang="en">
<head>
    @include('layouts.head')
</head>
<body>
<div id="overlayer"></div>
<span class="loader">
  <span class="loader-inner"></span>
</span>
<div class="wrapper">
    <!-- Sidebar  -->
{{--    @include('layouts.sidebar')--}}
    <!-- Page Content  -->
    <div class="content">
        <header>
            @include('layouts.navigation')
        </header>
        <main>
            @yield('content')
        </main>
    </div>
</div>
@include('layouts.scripts')
</body>
</html>
