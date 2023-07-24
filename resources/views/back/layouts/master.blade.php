<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="rtl">
<!-- BEGIN: Head-->

<head>
    @include('back.partials.meta')
    <title>
        @isset($title)
            {{  $title }}
        @else
            {{ option('info_site_title') }}
        @endisset
    </title>

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/back/app-assets/vendors/css/vendors-rtl.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/back/app-assets/vendors/css/ui/prism.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/back/app-assets/vendors/css/extensions/toastr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/back/app-assets/vendors/css/forms/select/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/back/app-assets/plugins/select2totree/select2totree.css') }}">

    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/back/app-assets/css-rtl/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/back/app-assets/css-rtl/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/back/app-assets/css-rtl/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/back/app-assets/css-rtl/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/back/app-assets/css-rtl/themes/dark-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/back/app-assets/css-rtl/themes/semi-dark-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/back/app-assets/css-rtl/plugins/extensions/toastr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/back/app-assets/css-rtl/plugins/animate/animate.min.css') }}">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/back/app-assets/css-rtl/core/menu/menu-types/vertical-menu.css') }}">
    @stack('styles')
    <!-- END: Page CSS-->

    @include('back.partials.global-css')

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'user' => auth()->user(),
            'csrfToken' => csrf_token(),
            'vapidPublicKey' => config('webpush.vapid.public_key'),
            'pusher' => [
                'key' => config('broadcasting.connections.pusher.key'),
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
            ],
       ]) !!};
    </script>

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern 2-columns  navbar-floating footer-static {{ user_option('menu_type') == 'collapsed' ? 'menu-collapsed' : '' }}  {{ user_option('theme_color') == 'dark' ? 'semi-dark-layout' : '' }}" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns" data-layout="semi-dark-layout">

    <!-- BEGIN: Header-->
    @include('back.partials.header')
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    @include('back.partials.sidebar')
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    @yield('content')
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    @include('back.partials.footer')
    <!-- END: Footer-->

    <audio id="notification-sound" class="d-none">
        <source src="{{ asset('public/back/app-assets/sounds/notification.ogg') }}" type="audio/ogg">
        Your browser does not support the audio element.
    </audio>


    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('public/back/app-assets/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('public/back/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/back/app-assets/plugins/select2totree/select2totree.js') }}"></script>
    <script src="{{ asset('public/back/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
    <script src="{{ asset('public/back/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('public/back/app-assets/vendors/js/ui/prism.min.js') }}"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('public/back/app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('public/back/app-assets/js/core/app.js') }}"></script>

    @toastr_render

    <script src="{{ asset('public/back/assets/js/helpers.js') }}?v=1.1"></script>
    <script src="{{ asset('public/back/assets/js/scripts.js') }}?v=1.1"></script>
    <!-- END: Theme JS-->

    <script>
        let BASE_URL = "{{ route('admin.dashboard') }}";
        let FRONT_URL = "{{ env('WEBSITE_URL') }}";

        let APP_FONT_FAMILY = "{{ user_option('theme_font', 'IransansDN') }}";
    </script>

    <!-- BEGIN: Page JS-->
    @stack('scripts')

    <!-- END: Page JS-->

{{--    <script src="{{ asset(mix('js/app.js')) }}"></script>--}}
{{--    <script src="{{ asset('public/back/assets/js/echo.js') }}"></script>--}}
{{--    <script src="{{ asset('public/back/assets/js/web-push.js') }}"></script>--}}
</body>
<!-- END: Body-->

</html>
