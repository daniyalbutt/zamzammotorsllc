
<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Sign In Basic</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-style-mode" content="1">
    <!-- Place favicon.ico in the root directory -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.png">
   @include('includes.css')

</head>

<body class="body-area">

    <div class="container-xxl">
        <!-- register area start-->
        <div class="authentication-wrapper basic-authentication">
            <div class="authentication-inner">
                <div class="card__wrapper">
                    <div class="authentication-top text-center mb-20">
                        <a href="javascript:;" class="authentication-logo logo-black">
                            <img src="assets/images/logo/logo.svg" alt="logo">
                        </a>
                        <a href="javascript:;" class="authentication-logo logo-white">
                            <img src="{{ asset('img/logo.png') }}" alt="logo">
                        </a>
                        <h4 class="mb-15">Welcome to {{ config('app.name', 'Laravel') }}</h4>
                        <p class="mb-15">Please sign-in to your account and start the adventure</p>
                    </div>
                    <form class="" action="{{route('login')}}" method="post">
                        @csrf
                        <div class="from__input-box">
                            <div class="form__input-title">
                                <label for="nameEmail">Email or Username</label>
                            </div>
                            <div class="form__input">
                                <input class="form-control" name="email" id="email" type="email" placeholder="test@mail.com" required>
                            </div>
                        </div>
                        <div class="from__input-box">
                            <div class="form__input-title d-flex justify-content-between">
                                <label for="passwordInput">Password</label>
                                <a href="auth-forgot-password-basic.html">
                                    <small>Forgot Password?</small>
                                </a>
                            </div>
                            <div class="form__input">
                                <input class="form-control" type="password" name="password" id="password" required>
                                <div class="pass-icon" id="passwordToggle"><i
                                        class="fa-sharp fa-light fa-eye-slash"></i></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember-me">
                                <label class="form-check-label" for="remember-me">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary w-100" type="submit">Sign in</button>
                        </div>
                    </form>
                    <p class="text-center">
                        <span>New on our platform?</span>
                        <a href="signup-basic.html">
                            <span>Create an account</span>
                        </a>
                    </p>
                   
                </div>
            </div>
        </div>
        <!-- register area end-->
    </div>

    <!-- Back to top start -->
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
    <!-- Back to top end -->

    <!-- JS here -->
    <script src="assets/js/vendor/jquery-3.7.0.js"></script>
    <script src="assets/js/vendor/isotope.pkgd.js"></script>
    <script src="assets/js/vendor/bootstrap.bundle.min.js"></script>
    <script src="assets/js/vendor/magnific-popup.min.js"></script>
    <script src="assets/js/vendor/ajax-form.js"></script>
    <script src="assets/js/vendor/jquery.repeater.js"></script>
    <script src="assets/js/plugins/waypoints.min.js"></script>
    <script src="assets/js/plugins/dayjs.min.js"></script>
    <script src="assets/js/plugins/loader.js"></script>
    <script src="assets/js/plugins/jsvectormap.min.js"></script>
    <script src="assets/js/plugins/world-merc.js"></script>
    <script src="assets/js/plugins/swiper-bundle.min.js"></script>
    <script src="assets/js/plugins/popper.min.js"></script>
    <script src="assets/js/plugins/simplebar.min.js"></script>
    <script src="assets/js/plugins/simplebar-active.js"></script>
    <script src="assets/js/plugins/backtotop.js"></script>
    <script src="assets/js/plugins/smooth-scrollbar.js"></script>
    <script src="assets/js/plugins/cleave.min.js"></script>
    <script src="assets/js/plugins/datatables.min.js"></script>
    <script src="assets/js/plugins/jquery.dataTables.min.js"></script>
    <script src="assets/js/plugins/dataTables.bootstrap5.min.js"></script>
    <script src="assets/js/plugins/dataTables.buttons.min.js"></script>
    <script src="assets/js/plugins/jszip.min.js"></script>
    <script src="assets/js/plugins/pdfmake.min.js"></script>
    <script src="assets/js/plugins/vfs_fonts.js"></script>
    <script src="assets/js/plugins/buttons.html5.min.js"></script>
    <script src="assets/js/plugins/buttons.print.min.js"></script>
    <script src="assets/js/plugins/buttons.colVis.min.js"></script>
    <script src="assets/js/plugins/steps-form.js"></script>
    <script src="assets/js/plugins/dropify.min.js"></script>
    <script src="assets/js/plugins/dropzone.js"></script>
    <script src="assets/js/plugins/tinymce.min.js"></script>
    <script src="assets/js/plugins/custom.js"></script>
    <script src="assets/js/plugins/typeahead.bundle.min.js"></script>
    <script src="assets/js/plugins/bloodhound.js"></script>
    <script src="assets/js/plugins/select2.full.min.js"></script>
    <script src="assets/js/plugins/jquery.timepicker.min.js"></script>
    <script src="assets/js/plugins/flatpickr.js"></script>
    <script src="assets/js/plugins/tagify.js"></script>
    <script src="assets/js/plugins/jquery-ui.min.js"></script>
    <script src="assets/js/plugins/sweetalert2.all.min.js"></script>
    <script src="assets/js/plugins/apexcharts.min.js"></script>
    <script src="assets/js/plugins/fullcalendar.min.js"></script>
    <script src="assets/js/plugins/ion.rangeSlider.min.js"></script>
    <script src="assets/js/vendor/custom-tagify.js"></script>
    <script src="assets/js/vendor/height-equal.js"></script>
    <script src="assets/js/vendor/custom-chart.js"></script>
    <script src="assets/js/vendor/rangeslider-script.js"></script>
    <script src="assets/js/vendor/jquery.barrating.js"></script>
    <script src="assets/js/vendor/rating-script.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/vendor/sidebar.js"></script>

</body>

</html>