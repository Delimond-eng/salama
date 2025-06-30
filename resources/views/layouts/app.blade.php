<!DOCTYPE html>

<html lang="en" class="light">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <meta charset="utf-8">
    <link href="dist/images/security.svg" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Icewall admin for weeding app">
    <meta name="keywords" content="Rapid Tech Property">
    <meta name="author" content="Gaston Delimond Dev">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ Auth::user()->id }}">
    <title>Salama Plateforme</title>
    <!-- BEGIN: CSS Assets-->
    <link rel="stylesheet" href="{{ asset("dist/css/vendors/tom-select.css") }}">
    <link rel="stylesheet" href="{{ asset("dist/css/vendors/zoom-vanilla.css") }}">
    <link rel="stylesheet" href="{{ asset('dist/css/vendors/tippy.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/vendors/litepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/vendors/tiny-slider.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/themes/rubick/side-nav.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/vendors/leaflet.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/vendors/simplebar.css') }}">
    <link rel="stylesheet" href="{{ asset("dist/css/vendors/toastify.css") }}">
    <link rel="stylesheet" href="{{ asset("dist/js/vendors/sweetalert2/sweetalert2.min.css") }}">
    <link rel="stylesheet" href="{{ asset("dist/css/vendors/highlight.css") }}">
    <link rel="stylesheet" href="{{ asset("dist/css/vendors/tippy.css") }}">
    <link rel="stylesheet" href="{{ asset('dist/css/components/mobile-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/app.css') }}">
    @stack("styles")

    <!-- END: CSS Assets-->
</head>
<!-- BEGIN: JS Assets-->
<body>

<div class="rubick px-5 sm:px-8 py-5 before:content-[''] before:bg-gradient-to-b before:from-theme-1 before:to-theme-2 dark:before:from-darkmode-800 dark:before:to-darkmode-800 before:fixed before:inset-0 before:z-[-1]">
    <!-- BEGIN: Mobile Menu -->
    @include("components.mobile_menu")
    <!-- END: Mobile Menu -->
    <div class="mt-[4.7rem] flex md:mt-0">
        <!-- BEGIN: Top Bar -->
        @include("components.side_nav")
        <!-- END: Top Bar -->
        @yield("content")
    </div>
</div>


<!-- BEGIN: Js assets -->
 <!-- @vite('resources/js/app.js') -->
<script src="{{ asset('dist/js/vendors/dom.js') }}"></script>
<script src="{{ asset('dist/js/vendors/tailwind-merge.js') }}"></script>
<script src="{{ asset("dist/js/vendors/tom-select.js") }}"></script>
<script src="{{ asset("dist/js/vendors/image-zoom.js") }}"></script>
<script src="{{ asset("dist/js/vendors/alert.js") }}"></script>
<script src="{{ asset('dist/js/vendors/lucide.js') }}"></script>
<script src="{{ asset('dist/js/vendors/tab.js') }}"></script>
<script src="{{ asset('dist/js/vendors/accordion.js') }}"></script>
<script src="{{ asset('dist/js/vendors/tippy.js') }}"></script>
<script src="{{ asset('dist/js/vendors/dayjs.js') }}"></script>
<script src="{{ asset('dist/js/vendors/litepicker.js') }}"></script>
<script src="{{ asset('dist/js/vendors/popper.js') }}"></script>
<script src="{{ asset('dist/js/vendors/dropdown.js') }}"></script>
<script src="{{ asset('dist/js/vendors/tiny-slider.js') }}"></script>
<script src="{{ asset('dist/js/vendors/transition.js') }}"></script>
<script src="{{ asset('dist/js/vendors/chartjs.js') }}"></script>
<script src="{{ asset('dist/js/vendors/leaflet-map.js') }}"></script>
<script src="{{ asset("dist/js/vendors/toastify.js") }}"></script>
<script src="{{ asset("dist/js/vendors/pristine.js") }}"></script>
<script src="{{ asset('dist/js/vendors/axios.js') }}"></script>
<script src="{{ asset('dist/js/utils/colors.js') }}"></script>
<script src="{{ asset('dist/js/utils/helper.js') }}"></script>
<script src="{{ asset('dist/js/vendors/simplebar.js') }}"></script>
<script src="{{ asset('dist/js/vendors/modal.js') }}"></script>
<script src="{{ asset('dist/js/vendors/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('dist/js/components/base/theme-color.js') }}"></script>
<script src="{{ asset('dist/js/components/base/lucide.js') }}"></script>
<script src="{{ asset('dist/js/components/base/tippy.js') }}"></script>
<script src="{{ asset('dist/js/components/base/litepicker.js') }}"></script>
<script src="{{ asset('dist/js/components/report-line-chart.js') }}"></script>
<script src="{{ asset('dist/js/components/report-pie-chart.js') }}"></script>
<script src="{{ asset('dist/js/components/report-donut-chart.js') }}"></script>
<script src="{{ asset('dist/js/components/report-donut-chart-1.js') }}"></script>
<script src="{{ asset('dist/js/components/simple-line-chart-1.js') }}"></script>
<script src="{{ asset('dist/js/components/base/tiny-slider.js') }}"></script>
<script src="{{ asset('dist/js/themes/rubick.js') }}"></script>
<script src="{{ asset('dist/js/components/mobile-menu.js') }}"></script>
<script src="{{ asset('dist/js/components/themes/rubick/top-bar.js') }}"></script>

<script src="{{ asset("assets/js/libs/vue2.js") }}"></script>
{{-- For pusher notification  --}}
<!-- <script type="module" src="{{ asset("assets/js/scripts/talkiewalkie_controller.js") }}"></script> -->
@stack("scripts")
<!-- END: JS Assets-->
</body>

</html>
