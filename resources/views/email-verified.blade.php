@extends('layouts.validate.validate')
<!-- ============================================================== -->
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->
@section('content')
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
        </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <section id="wrapper">
        <section id="wrapper">
            <div class="login-register" style="background-image:url('{{asset('assets/images/background/login4.jpg')}}');">
                <div class="login-box card">
                    <div class="card-block">
                        <div class="container" style="margin-top: 10px;">
                            <p>
                                Your account is verified, please <a href="{{Config::get('global.LOGIN_URL')}}"> Login</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>

@endsection('content')
