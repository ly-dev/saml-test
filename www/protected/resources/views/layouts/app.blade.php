<!DOCTYPE html>
<html lang="en">
@inject('bladeutil', 'App\Services\BladeUtilService')
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- force desktop view on mobile device -->
    <meta name="viewport" content="width=1200">
    <!-- meta name="viewport" content="width=device-width, initial-scale=1" -->
    <meta name="csrf-token" content="{{csrf_token()}}">

    <title>SAML</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.16/css/dataTables.bootstrap.min.css" integrity="sha256-PbaYLBab86/uCEz3diunGMEYvjah3uDFIiID+jAtIfw=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" integrity="sha256-xJOZHfpxLR/uhh1BwYFS5fhmOAdIRQaiOul5F/b7v3s=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" integrity="sha256-yMjaV542P+q1RnH6XByCPDfUFhmOafWbeLPmqKh11zo=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.25/daterangepicker.css" />

    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    @stack('module-styles')
</head>
<body id="app-layout">
    <div id="site-content-wrap">
        @include ('components.site-header')
        @yield('content')
    </div>
    <div id="site-footer-wrap">
        <div class="footer-first-row">
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <p class="name">
                            Example.com
                        </p>
                        <p>
                            <a href="{{ url('page/terms-of-use') }}" target="_blank">Terms of Use</a>
                        </p>
                        <p>
                            <a href="{{ url('page/privacy-policy') }}" target="_blank">Privacy Policy</a>
                        </p>

                    </div>
                    <div class="col-sm-4">
                        <p class="contact">
                            Contact
                        </p>
                        <p>
                            <a href="mailto:webadmin@example.com">webadmin@example.com</a>
                        </p>
                    </div>
                    <div class="col-sm-4">
                        <p class="follow">
                            Follow
                        </p>
                        <p class="follow-links">
                            <a href="https://www.facebook.com/" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                            <a href="https://twitter.com/" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                            <a href="https://www.linkedin.com/" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-second-row">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        &copy; <?php echo date('Y'); ?> by Ricardo
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.2/lodash.min.js" integrity="sha256-Cv5v4i4SuYvwRYzIONifZjoc99CkwfncROMSWat1cVA=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.16/js/jquery.dataTables.js" integrity="sha256-ytJ1zZmF4c0QIOnJ1CLrOlbMSxZJM3vumRNZZV5tkLw=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.16/js/dataTables.bootstrap.min.js" integrity="sha256-X/58s5WblGMAw9SpDtqnV8dLRNCawsyGwNqnZD0Je/s=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.full.min.js" integrity="sha256-no4wUCj/rrqZgEqUJ0pQMJFwIjzKSyx5WZqXpXPU0vU=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment-with-locales.min.js" integrity="sha256-vvT7Ok9u6GbfnBPXnbM6FVDEO8E1kTdgHOFZOAXrktA=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js" integrity="sha256-5YmaxAwMjIpMrVlK84Y/+NjCpKnFYa8bWWBbUHSBGfU=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.25/daterangepicker.js"></script>

    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/app-tooltip.js') }}"></script>
    @include ('components.app-properties')
    @stack('module-scripts')
</body>
</html>
