{{--@extends('adminlte::login')--}}
<!DOCTYPE html>
<!--suppress ALL -->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/images/memberme_icon.ico')}}">
    <title> @yield('title') </title>
    <!-- Bootstrap Core CSS -->
    <link href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <!--Bootstrap Toggle-->
    <link href="{{asset('assets/plugins/bootstrap-toggle/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <!-- page CSS -->
    <link href="{{asset('assets/plugins/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet"/>
    <!-- xeditable css -->
    <link href="{{asset('assets/plugins/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css')}}" rel="stylesheet"/>
    <!-- Custom CSS -->
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
    <!--My Changings in Current Template-->
    <link href="{{asset('assets/css/new-style.css')}}" rel="stylesheet"/>
    <!-- FWD 3D Carousel -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/plugins/ultimate-3d-carousel/load/html_content.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/plugins/ultimate-3d-carousel/load/skin_modern_silver.css')}}"/>
    <script type="text/javascript" src="{{asset('assets/plugins/ultimate-3d-carousel/java/FWDUltimate3DCarousel.js')}}"></script>
    <!-- You can change the theme colors from here -->
    <link href="{{asset('assets/css/colors/megna-dark.css')}}" id="theme" rel="stylesheet">

    <!--HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries-->
    <!--WARNING: Respond.js doesn't work if you view the page via file:-->
    <!--[if lt IE 9]-->
    <script src="{{asset('assets/plugins/html5-support/html5shiv.js')}}"></script>
    <script src="{{asset('assets/plugins/html5-support/respond.min.js')}}"></script>
    <!--[endif]-->
</head>

<body>
    @yield('content')
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
    <!--Jquery Browser Detection Plugin-->
    <script src="{{asset('assets/plugins/jquery-browser/jquery.browser.min.js')}}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{asset('assets/plugins/bootstrap/js/tether.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-select/bootstrap-select.min.js')}}" type="text/javascript"></script>
    <!--Bootstrap Toggle-->
    <script src="{{asset('assets/plugins/bootstrap-toggle/bootstrap-toggle.min.js')}}"></script>
    <!--Moment JS-->
    <script src="{{asset('assets/plugins/moment/moment.js')}}"></script>
    <!--XEditable Bootstrap-->
    <script type="text/javascript"
            src="{{asset('assets/plugins/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.js')}}"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{asset('assets/js/jquery.slimscroll.js')}}"></script>
    <!--Wave Effects -->
    <script src="{{asset('/assets/js/waves.js')}}"></script>
    <!--Menu sidebar -->
    <script src="{{asset('assets/js/sidebarmenu.js')}}"></script>
    <!--stickey kit -->
    <script src="{{asset('assets/plugins/sticky-kit-master/dist/sticky-kit.min.js')}}"></script>

    <!--Data Masks - For input fields -->
    <script src="{{asset('assets/js/mask.js')}}"></script>
    <!--Bootstrap Validation-->
    <script src="{{asset('assets/js/validation.js')}}"></script>
    <!--Custom JavaScript -->
    <script src="{{asset('assets/js/custom.min.js')}}"></script>
    <script src="{{asset('assets/js/new-custom.js')}}"></script>
    <script src="{{asset('js/site.js')}}"></script>
    <!-- This is data table -->
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <!-- start - This is for export functionality only -->
    <script src="{{asset('assets/plugins/datatables-extensions/buttons.html5.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-extensions/buttons.print.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-extensions/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-extensions/jszip.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-extensions/pdfmake.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-extensions/vfs_fonts.js')}}"></script>
</body>

</html>
