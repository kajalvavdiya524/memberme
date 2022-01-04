@extends('layouts.validate.validate')
@section('title','Profile Details')
@section('content')

<div class="div-my-preloader">
    <div class="modal-content">
        <div class="my-preloader preloader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                        stroke-miterlimit="10"/>
            </svg>
        </div>
        <div class="modal-header">
            <h4 id="profile-setting" class="modal-title">
                Profile Detail
            </h4>
        </div>
        <form action="{{route('save.user.details')}}" method="post">
            {{csrf_field()}}
            <div class="modal-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs profile-tab" role="tablist">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#profile" role="tab">Profile</a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="profile" role="tabpanel">
                    <div class="card-block">
                        <div id="user-profile-details">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-md-5 my-label">First Name:</div>
                                        <div class="col-md-6">

                                            <div class="form-group has-feedback {{ $errors->has('first_name') ? 'has-error' : '' }}">
                                                <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}"
                                                       placeholder="Enter First Name">
                                                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                                @if ($errors->has('first_name'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('first_name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 my-label">Last Name :</div>
                                        <div class="col-md-6">
                                            <div class="form-group has-feedback {{ $errors->has('last_name') ? 'has-error' : '' }}">
                                                <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}"
                                                       placeholder="Enter Last Name">
                                                @if ($errors->has('last_name'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('last_name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-md-5 my-label">Date Added:</div>
                                        <div class="col-md-6">
                                                    <span>
                                                        {{$user->created_at}}
                                                    </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 my-label">Full Name:</div>
                                        <div class="col-md-6">
                                            {{ $user->first_name?:'First Name' }} {{ $user->last_name?:'Last Name' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="row">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 my-label">Email :</div>
                                        <div class="col-md-6">
                                            {{$user->email}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="row">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 my-label">Phone :</div>
                                        <div class="col-md-6">
                                            <div class="form-group has-feedback {{ $errors->has('contact_no') ? 'has-error' : '' }}">
                                                <input type="text" name="contact_no" class="form-control" value="{{ old('contact_no') }}"
                                                       placeholder="Enter Contact No">
                                                @if ($errors->has('contact_no'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('contact_no') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>{{--
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-md-5 my-label">Update Password:
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button"
                                                    class="btn btn-info btn-sm btn-rounded">
                                                <i
                                                        class="fa fa-envelope"></i> Send
                                                Email
                                            </button>
                                    </div>
                                </div>--}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-10">
                                    <div class="row">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 my-label">Notes :</div>
                                        <div class="col-md-8">
                                            <textarea  name="notes" id="user_notes" cols="40" rows="5" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row"></div>
                                    <div class="row">
                                        <div class="col-lg-4"></div>
                                        <div class="col-lg-4">
                                            <button type="submit" class="btn btn-block btn-outline-success" >Submit</button>
                                        </div>
                                        <div class="col-lg-4"></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </form>

    </div>
    <!-- /.modal-content -->
</div>

@endsection
