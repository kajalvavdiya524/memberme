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
        <div class="login-register my-bg-login" style="background-image:url('{{asset("assets/images/background/login4.jpg   ")}}');">
            <div class="login-box card">
                <div class="card-block">
                    <form class="form-horizontal form-material" id="loginform" action="login" method="post">
                        <h3 class="box-title m-b-20">Sign In</h3>
                        {{\Illuminate\Support\Facades\Session::get('verify')}}
                        {{\Illuminate\Support\Facades\Session::get('reset')}}
                        <span style="padding-left: 15%">{{\Illuminate\Support\Facades\Session::get('verify1')}}</span>
                        <span style="padding-left: 12%">{{\Illuminate\Support\Facades\Session::get('verify2')}}</span>
                        <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                   placeholder="{{ trans('adminlte::adminlte.email') }}">
                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                            @if ($errors->has('email'))
                                <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                            @endif
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                            <input type="password" name="password" class="form-control"
                                   placeholder="{{ trans('adminlte::adminlte.password') }}">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            @if ($errors->has('password'))
                                <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="checkbox checkbox-primary pull-left p-t-0">
                                    <input id="checkbox-signup" type="checkbox">
                                    <label for="checkbox-signup"> Remember me </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </div>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;</span> | <span> &nbsp;&nbsp;&nbsp;&nbsp; </span>
                                <a href="{{ url(config('adminlte.password_reset_url', 'password/reset')) }}"
                                   class="text-center"
                                > Forgot pwd? </a>
                            </div>
                        </div>
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <!--<button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light"-->
                                <!--type="submit">Log In-->
                                <!--</button>-->
                                {{csrf_field()}}
                                <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light"> Login </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 m-t-10 text-center">
                                <div class="social">
                                    <a href="javascript:void(0)" class="btn  btn-facebook" data-toggle="tooltip"
                                       title="Login with Facebook"> <i aria-hidden="true" class="fa fa-facebook"></i> </a>
                                    <a href="javascript:void(0)" class="btn btn-googleplus" data-toggle="tooltip"
                                       title="Login with Google"> <i aria-hidden="true" class="fa fa-google-plus"></i> </a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-b-0">
                            <div class="col-sm-12 text-center">
                                <p>Don't have an account? <a href="register" class="text-info m-l-5"><b>Sign
                                            Up</b></a></p>
                            </div>
                        </div>
                    </form>
                    <form class="form-horizontal" id="recoverform" action="{{url('password/reset')}}" method="post">
                        {{csrf_field()}}
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <h3>Recover Password</h3>
                                <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" type="text" required="" placeholder="Email"></div>
                        </div>
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light"
                                        type="submit">Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
@endsection
