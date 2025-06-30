<!DOCTYPE html>

<html lang="en" class="light">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <meta charset="utf-8">
    <link href="dist/images/security.svg" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Salama">
    <meta name="keywords" content="Rapid Tech Property">
    <meta name="author" content="Gaston Delimond Dev">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Salama Plateforme</title>
    <!-- BEGIN: CSS Assets-->
    <link rel="stylesheet" href="{{ asset("dist/css/vendors/toastify.css") }}">
    <link rel="stylesheet" href="{{ asset("dist/css/vendors/highlight.css") }}">
    <link rel="stylesheet" href="{{ asset("dist/css/vendors/tippy.css") }}">
    <link rel="stylesheet" href="{{ asset('dist/css/app.css') }}" />
    <!-- END: CSS Assets-->
</head>
<!-- BEGIN: JS Assets-->
<body class="login">
    @yield("content")
    <!-- BEGIN: Js assets -->
    <script src="{{ asset("dist/js/vendors/dom.js") }}"></script>
    <script src="{{ asset("dist/js/vendors/tailwind-merge.js") }}"></script>
    <script src="{{ asset("dist/js/vendors/toastify.js") }}"></script>
    <script src="{{ asset("dist/js/vendors/lucide.js") }}"></script>
    <script src="{{ asset("dist/js/vendors/modal.js") }}"></script>
    <script src="{{ asset("dist/js/vendors/pristine.js") }}"></script>
    <script src="{{ asset("dist/js/components/base/theme-color.js") }}"></script>
    <script src="{{ asset("dist/js/components/base/lucide.js") }}"></script>
    <script src="{{ asset('assets/js/libs/vue2.js') }}"></script>
    @stack("scripts")
    <!-- END: JS Assets-->
</body>

</html>
