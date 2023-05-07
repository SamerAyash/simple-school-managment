<!DOCTYPE html>
<html direction="{{app()->getLocale()=='ar'?'rtl':'ltr'}}" dir="{{app()->getLocale()=='ar'?'rtl':'ltr'}}"
      style="direction: {{app()->getLocale()=='ar'?'rtl':'ltr'}}">

@include('layout.head')
@stack('style')

<!--begin::Body-->
<body id="kt_body"
      class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">

<!--begin::Main-->
<!--begin::Header Mobile-->
<div id="kt_header_mobile" class="header-mobile align-items-center  header-mobile-fixed ">
    <!--begin::Logo-->
    <a href="#">
    </a>
    <!--end::Logo-->

    <!--begin::Toolbar-->
    <div class="d-flex align-items-center">
        <!--begin::Aside Mobile Toggle-->
        <button class="btn p-0 burger-icon burger-icon-left" id="kt_aside_mobile_toggle">
            <span></span>
        </button>
        <!--end::Aside Mobile Toggle-->
    </div>
    <!--end::Toolbar-->
</div>
<!--end::Header Mobile-->
<div class="d-flex flex-column flex-root">
@include('layout.slider')

<!--begin::Wrapper-->
    <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
    @include('layout.header')
    <!--end::Content-->

@yield('content')

@include('layout.footer')
        <script>
            function logout(){$('#logoutForm').submit()}
        </script>
@stack('js')
</body>
</html>
