<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="rtl">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description"
        content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>مشفى السلام</title>
    <link rel="apple-touch-icon" href="{{asset('logo.jpg')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('logo.jpg')}}">

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/vendors/css/vendors-rtl.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/vendors/css/charts/apexcharts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/vendors/css/extensions/toastr.min.css') }}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/bootstrap-extended.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/colors.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/components.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/themes/dark-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/themes/bordered-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/themes/semi-dark-layout.css')}}">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />



    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/app-assets/css-rtl/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/pages/dashboard-ecommerce.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/plugins/charts/chart-apex.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/app-assets/css-rtl/plugins/extensions/ext-component-toastr.css') }}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/custom-rtl.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/style-rtl.css') }}">
    <!-- END: Custom CSS-->

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .navigation-main .nav-item a {
            font-family: 'Cairo', sans-serif !important;
        }


        body {
            font-family: 'Cairo', sans-serif;
        }

        .main-menu.menu-light .navigation>li.active>a {
            background: #00a102;
            background: linear-gradient(-118deg, #00a102, #00a102);
            box-shadow: 0 0 10px 1px #00a102;
            color: #FFFFFF;
            font-weight: 400;
            border-radius: 4px;
        }
    </style>
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click"
    data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Header-->
    <nav
        class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">
        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon"
                                data-feather="menu"></i></a></li>
                </ul>
                <ul class="nav navbar-nav">
                    <li class="nav-item d-none d-lg-block"><a class="nav-link bookmark-star"></a>
                        <h2 style="font-weight: bold;font-family: 'Cairo', sans-serif;color: #00a102;">مشفى السلام
                        </h2>
                    </li>
                </ul>
            </div>
            <ul class="nav navbar-nav align-items-center ms-auto">
                <li class="nav-item d-none d-lg-block">
                    <h4 style="font-weight: bold;font-family: 'Cairo', sans-serif;color: #00a102;">
                        {{ auth()->user()->roles->map(function ($role) {
    return $role->name === 'Super Admin' ? 'المدير' : $role->name;
})->join(', ') }}
                        -
                        {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                    </h4>
                </li>
                <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link"
                        id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <div class="user-nav d-sm-flex d-none"><span class="user-name fw-bolder"></span><span
                                class="user-status"></span></div><span class="avatar"><img class="round"
                                src="{{asset('logo.jpg')}}" alt="avatar" height="40" width="40"><span
                                class="avatar-status-online"></span></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user"
                        style="font-weight: bold;font-family: 'Cairo', sans-serif;color: #00a102;">
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="me-50" data-feather="user"></i> المعلومات الشخصية
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="me-50" data-feather="power"></i> تسجيل الخروج
                            </button>
                        </form>
                    </div>

                </li>
            </ul>
        </div>
    </nav>
    <ul class="main-search-list-defaultlist-other-list d-none">
        <li class="auto-suggestion justify-content-between"><a
                class="d-flex align-items-center justify-content-between w-100 py-50">
                <div class="d-flex justify-content-start"><span class="me-75"
                        data-feather="alert-circle"></span><span>No results found.</span></div>
            </a></li>
    </ul>
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item me-auto"><a class="navbar-brand" href="/">
                        <span class="brand-logo">
                            <img src="{{ asset('logo.jpg') }}" width="30" />
                        </span>
                        <h2 class="brand-text" style="color: #00a102;">مشفى السلام</h2>
                    </a></li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i
                            class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i
                            class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary"
                            data-feather="disc" data-ticon="disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main">
                @if (auth()->user()->hasRole('1فني الأشعة') || auth()->user()->hasRole('Super Admin'))
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="/dashboard">
                            <i class="fa-solid fa-building-columns"></i>
                            <span class="menu-title text-truncate">لوحة التحكم</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasRole('1فني الأشعة') || auth()->user()->hasRole('Super Admin'))
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="{{ route('departments.index') }}">
                            <i class="fa-solid fa-building-columns"></i>
                            <span class="menu-title text-truncate">الأقسام</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasRole('Super Admin'))
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="{{ route('visits.index') }}">
                            <i class="fa-solid fa-hospital-user"></i>
                            <span class="menu-title text-truncate">الزيارات</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasRole('المريض'))
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="/dashboard">
                            <i class="fa-solid fa-building-columns"></i>
                            <span class="menu-title text-truncate">حجز موعد</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="{{ route('visits.my') }}">
                            <i class="fa-solid fa-hospital-user"></i>
                            <span class="menu-title text-truncate">زياراتي</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasRole('الدكتور'))
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="/dashboard">
                            <i class="fa-solid fa-building-columns"></i>
                            <span class="menu-title text-truncate">زياراتي</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="{{ route('doctor.appointments') }}">
                            <i class="fa-solid fa-hospital-user"></i>
                            <span class="menu-title text-truncate">مناوباتي</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasRole('موظف الاستقبال') || auth()->user()->hasRole('Super Admin'))
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="{{ route('reception.index') }}">
                            <i class="fa-solid fa-building-columns"></i>
                            <span class="menu-title text-truncate">الاستقبال</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasRole('1فني الأشعة') || auth()->user()->hasRole('Super Admin'))
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="{{ route('appointments.index') }}">
                            <i class="fa-solid fa-calendar-check"></i>
                            <span class="menu-title text-truncate">المواعيد</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasRole('فني الأشعة'))
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="/dashboard">
                            <i class="fa-solid fa-building-columns"></i>
                            <span class="menu-title text-truncate">طلبات الصور الحالية</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="{{ route('xrays.index') }}">
                            <i class="fa-solid fa-x-ray"></i>
                            <span class="menu-title text-truncate">صور الأشعة</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasRole('فني العمليات'))
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="/dashboard">
                            <i class="fa-solid fa-building-columns"></i>
                            <span class="menu-title text-truncate">العمليات الحالية</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="{{ route('surgeries.index') }}">
                            <i class="fa-solid fa-x-ray"></i>
                            <span class="menu-title text-truncate">كل العمليات</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasRole('ممرض الجناح'))
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="{{ route('nursing_requests.index') }}">
                            <i class="fa-solid fa-hand-holding-medical"></i>
                            <span class="menu-title text-truncate">طلبات الرعاية التمريضية</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasRole('فني المخبر'))
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="/dashboard">
                            <i class="fa-solid fa-building-columns"></i>
                            <span class="menu-title text-truncate">طلبات التحليل الحالية</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="{{ route('lab_tests.index') }}">
                            <i class="fa-solid fa-vials"></i>
                            <span class="menu-title text-truncate">تحاليل المختبر</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasRole('1فني الأشعة') || auth()->user()->hasRole('Super Admin'))
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="{{ route('prescriptions.index') }}">
                            <i class="fa-solid fa-file-prescription"></i>
                            <span class="menu-title text-truncate">الوصفات الطبية</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasRole('1فني الأشعة') || auth()->user()->hasRole('Super Admin'))
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="{{ route('surgeries.index') }}">
                            <i class="fa-solid fa-hospital-user"></i>
                            <span class="menu-title text-truncate">العمليات الجراحية</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasRole('1فني الأشعة') || auth()->user()->hasRole('Super Admin'))
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="{{ route('surgery_procedures.index') }}">
                            <i class="fa-solid fa-procedures"></i>
                            <span class="menu-title text-truncate">إجراءات العمليات</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasRole('1فني الأشعة') || auth()->user()->hasRole('Super Admin'))
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="{{ route('users.index') }}">
                            <i class="fa-solid fa-user-group"></i>
                            <span class="menu-title text-truncate">المستخدمين</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasRole('1فني الأشعة') || auth()->user()->hasRole('Super Admin'))
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="/roles">
                            <i class="fa-solid fa-circle-minus"></i>
                            <span class="menu-title text-truncate">الصلاحيات</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">
                @yield('content')
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light mt-4" style="background-color: white;">
        <p class=" mb-0"><span class="float-md-start d-none d-md-block">Copyright © 2025 . جميع الحقوق محفوظة.<a
                    class="ms-25" href="{{asset('logo.jpg')}}" target="_blank"> </a> <img class="round"
                    src="{{asset('logo.jpg')}}" alt="avatar" height="20" width="20"></span></span></p>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
    <!-- END: Footer-->


    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('/app-assets/vendors/js/vendors.min.js') }}"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('/app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('/app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('/app-assets/js/core/app.js') }}"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{ asset('/app-assets/js/scripts/pages/dashboard-ecommerce.js') }}"></script>
    <!-- END: Page JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="../../../app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script src="../../../app-assets/vendors/js/extensions/polyfill.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Page JS-->
    <script src="../../../app-assets/js/scripts/extensions/ext-component-sweet-alerts.js"></script>
    <!-- END: Page JS-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(window).on('load', function () {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // الحصول على مسار الصفحة الحالي
            var currentPath = window.location.pathname;

            // الحصول على جميع عناصر القائمة
            var menuItems = document.querySelectorAll(".navigation-main .nav-item a");

            menuItems.forEach(function (item) {
                if (item.href.includes(currentPath)) {
                    item.parentElement.classList.add("active");
                }
            });
        });
    </script>
    @stack('scripts')
</body>
<!-- END: Body-->

</html>