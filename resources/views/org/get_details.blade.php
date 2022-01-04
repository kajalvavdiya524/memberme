@extends('layouts.validate.validate')
@section('title','Organization Details')
@section('content')
<div class=" div-my-preloader">
    <div class="modal-content">
        <div class="my-preloader preloader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                        stroke-miterlimit="10"/>
            </svg>
        </div>
        <div class="modal-header">
            <h4 id="organization-detail" class="modal-title">
                Organization Details
            </h4>
        </div>
        <div class="modal-body">
            <div class="user-tabs">
                <!-- Tab panes -->
                <div class="tab-content">
                    <form action="{{route('save.org.details')}}" method="post">
                        {{csrf_field()}}

                        <div class="tab-pane active" id="org-detail" role="tabpanel">
                        <div class="p-20">
                            <div class="row org-details">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-md-5 my-label">Organization :</div>
                                        <div class="col-md-7">
                                            <div class="form-group has-feedback {{ $errors->has('name') ? 'has-error' : '' }}">
                                                <input type="text" name="name" class="form-control" value="{{ old('name')?:$organization->name }}"
                                                       placeholder="Enter Organization Name">
                                                @if ($errors->has('name'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <input type="hidden" name="organization_id" value="{{$organization->id}}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 my-label">Contact Name :</div>
                                        <div class="col-md-7">
                                            <div class="form-group has-feedback {{ $errors->has('contact_name') ? 'has-error' : '' }}">
                                                <input type="text" name="contact_name" class="form-control" value="{{ old('contact_name')?:$organization->owner->first_name. ' '. $organization->owner->last_name }}"
                                                       placeholder="Enter Contact Name">
                                                @if ($errors->has('contact_name'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('contact_name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 my-label">Contact Phone :</div>
                                        <div class="col-md-7">
                                            <div class="form-group has-feedback {{ $errors->has('contact_phone') ? 'has-error' : '' }}">
                                                <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone')?:$organization->owner->contact_no }}"
                                                       placeholder="Enter Contact Phone">
                                                @if ($errors->has('contact_phone'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('contact_phone') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 my-label">Industry :</div>
                                        <div class="col-md-7">
                                            <div class="form-group has-feedback {{ $errors->has('industry') ? 'has-error' : '' }}">
                                                <input type="text" name="industry" class="form-control" value="{{ old('industry') }}"
                                                       placeholder="Select Industry">
                                                @if ($errors->has('industry'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('industry') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-md-5 my-label">Account # :</div>
                                        <div class="col-md-7">{{--
                                            <div class="form-group has-feedback {{ $errors->has('account_no') ? 'has-error' : '' }}">
                                                <input type="text" name="account_no" class="form-control" value="{{ old('account_no') }}"
                                                       placeholder="Account No">
                                                @if ($errors->has('account_no'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('account_no') }}</strong>
                                                    </span>
                                                @endif
                                            </div>--}}

                                            {{$organization->id}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 my-label">Contact Email :</div>
                                        <div class="col-md-7">{{--
                                            <div class="form-group has-feedback {{ $errors->has('contact_email') ? 'has-error' : '' }}">
                                                <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email') }}"
                                                       placeholder="Conact Email">
                                                @if ($errors->has('contact_email'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('contact_email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>--}}
                                            {{$organization->owner->email}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 my-label">GST # :</div>
                                        <div class="col-md-7">
                                            <div class="form-group has-feedback {{ $errors->has('gst') ? 'has-error' : '' }}">
                                                <input type="text" name="gst" class="form-control" value="{{ old('gst') }}"
                                                       placeholder="GST">
                                                @if ($errors->has('gst'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('gst') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="m-t-20 m-b-20">
                            <div class="row org-postal-details">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h4 class="text-center text-muted">Physical Address</h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 my-label">Address 1 :</div>
                                        <div class="col-md-7">

                                            <div class="form-group has-feedback {{ $errors->has('physical_first_address') ? 'has-error' : '' }}">
                                                <input type="text" name="physical_first_address" class="form-control" value="{{ old('physical_first_address') }}"
                                                       placeholder="Physical First Address">
                                                @if ($errors->has('physical_first_address'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('physical_first_address') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 my-label">Address 2 :</div>
                                        <div class="col-md-7">
                                            <div class="form-group has-feedback {{ $errors->has('physical_second_address') ? 'has-error' : '' }}">
                                                <input type="text" name="physical_second_address" class="form-control" value="{{ old('physical_second_address') }}"
                                                       placeholder="Physical Second Address">
                                                @if ($errors->has('physical_second_address'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('physical_second_address') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 my-label">Suburb :</div>
                                        <div class="col-md-7">

                                            <div class="form-group has-feedback {{ $errors->has('physical_suburb') ? 'has-error' : '' }}">
                                                <input type="text" name="physical_suburb" class="form-control" value="{{ old('physical_suburb') }}"
                                                       placeholder="Physical Suburb">
                                                @if ($errors->has('physical_suburb'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('physical_suburb') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 my-label">City :</div>
                                        <div class="col-md-7">
                                            <div class="form-group has-feedback {{ $errors->has('physical_city') ? 'has-error' : '' }}">
                                                <input type="text" name="physical_city" class="form-control" value="{{ old('physical_city') }}"
                                                       placeholder="City">
                                                @if ($errors->has('physical_city'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('physical_city') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 my-label">Region :</div>
                                        <div class="col-md-7">
                                            <div class="form-group has-feedback {{ $errors->has('physical_region') ? 'has-error' : '' }}">
                                                <input type="text" name="physical_region" class="form-control" value="{{ old('physical_region') }}"
                                                       placeholder="Physical Region">
                                                @if ($errors->has('physical_region'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('physical_region') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-5 my-label">Postal Code:</div>
                                        <div class="col-md-7">

                                            <div class="form-group has-feedback {{ $errors->has('physical_postal_code') ? 'has-error' : '' }}">
                                                <input type="text" name="physical_postal_code" class="form-control" value="{{ old('physical_postal_code') }}"
                                                       placeholder="Postal Code">
                                                @if ($errors->has('physical_postal_code'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('physical_postal_code') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-5 my-label">Country :</div>
                                        <div class="col-md-7">
                                            <div class="form-group has-feedback {{ $errors->has('physical_country') ? 'has-error' : '' }}">
                                                <select name="physical_country" id="" class="form-control">
                                                    <option value="1">New Zealand</option>
                                                    <option value="2">Australia</option>
                                                </select>
                                                @if ($errors->has('physical_country'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('physical_country') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 my-label">Latitude :</div>
                                        <div class="col-md-7">
                                            <div class="form-group has-feedback {{ $errors->has('physical_latitude') ? 'has-error' : '' }}">
                                                <input type="text" name="physical_latitude" class="form-control" value="{{ old('physical_latitude') }}"
                                                       placeholder="Latitude">
                                                @if ($errors->has('physical_latitude'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('physical_latitude') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 my-label">Longitude :</div>
                                        <div class="col-md-7">
                                            <div class="form-group has-feedback {{ $errors->has('physical_longitude') ? 'has-error' : '' }}">
                                                <input type="text" name="physical_longitude" class="form-control" value="{{ old('physical_longitude') }}"
                                                       placeholder="Longitude">
                                                @if ($errors->has('physical_longitude'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('physical_longitude') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="m-t-20 m-b-20 show-md">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h4 class="text-center text-muted">
                                                Postal Address
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 my-label">Address 1 :</div>
                                        <div class="col-md-7">
                                            <div class="form-group has-feedback {{ $errors->has('postal_first_address') ? 'has-error' : '' }}">
                                                <input type="text" name="postal_first_address" class="form-control" value="{{ old('postal_first_address') }}"
                                                       placeholder="Postal Second Address">
                                                @if ($errors->has('postal_first_address'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('postal_first_address') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 my-label">Address 2 :</div>
                                        <div class="col-md-7">
                                            <div class="form-group has-feedback {{ $errors->has('postal_second_address') ? 'has-error' : '' }}">
                                                <input type="text" name="postal_second_address" class="form-control" value="{{ old('postal_second_address') }}"
                                                       placeholder="Postal Second Address">
                                                @if ($errors->has('postal_second_address'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('postal_second_address') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 my-label">Suburb :</div>
                                        <div class="col-md-7">
                                            <div class="form-group has-feedback {{ $errors->has('postal_suburb') ? 'has-error' : '' }}">
                                                <input type="text" name="postal_suburb" class="form-control" value="{{ old('postal_suburb') }}"
                                                       placeholder="Postal Suburb">
                                                @if ($errors->has('postal_suburb'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('postal_suburb') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 my-label">Postal Code :</div>
                                        <div class="col-md-7">
                                            <div class="form-group has-feedback {{ $errors->has('postal_postal_code') ? 'has-error' : '' }}">
                                                <input type="text" name="postal_postal_code" class="form-control" value="{{ old('postal_postal_code') }}"
                                                       placeholder="Postal Code">
                                                @if ($errors->has('postal_postal_code'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('postal_postal_code') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 my-label">City :</div>
                                        <div class="col-md-7">
                                            <div class="form-group has-feedback {{ $errors->has('postal_city') ? 'has-error' : '' }}">
                                                <input type="text" name="postal_city" class="form-control" value="{{ old('postal_city') }}"
                                                       placeholder="Postal City">
                                                @if ($errors->has('postal_city'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('postal_city') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 my-label">Region :</div>
                                        <div class="col-md-7">
                                            <div class="form-group has-feedback {{ $errors->has('postal_region') ? 'has-error' : '' }}">
                                                <input type="text" name="postal_country" class="form-control" value="{{ old('postal_region') }}"
                                                       placeholder="Postal Region">
                                                @if ($errors->has('postal_region'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('postal_region') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 my-label">Country :</div>
                                        <div class="col-md-7">

                                            <div class="form-group has-feedback {{ $errors->has('postal_country') ? 'has-error' : '' }}">
                                                <select name="postal_country" id="" class="form-control">
                                                    <option value="">Select Postal Country</option>
                                                    <option value="1">New Zealand</option>
                                                    <option value="2">Australia</option>
                                                </select>
                                                @if ($errors->has('postal_country'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('postal_country') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="m-t-20 m-b-20">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-md-5 my-label">Starting Member # :</div>
                                        <div class="col-md-7">
                                            <div class="form-group has-feedback {{ $errors->has('starting_member') ? 'has-error' : '' }}">
                                                <input type="text" name="starting_member" class="form-control" value="{{ old('starting_member')?: 1 }}"
                                                       placeholder="Enter Starting Member">
                                                @if ($errors->has('starting_member'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('starting_member') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 my-label">Next Member # :</div>
                                        <div class="col-md-7">
                                            <div class="form-group has-feedback {{ $errors->has('next_member') ? 'has-error' : '' }}">
                                                <input type="text" name="next_member" class="form-control" value="{{ old('next_member')?: 1 }}"
                                                       placeholder="Enter Next Member No">
                                                @if ($errors->has('next_member'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('next_member') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-md-5 my-label">Starting Receipt # :</div>
                                        <div class="col-md-7">
                                            <div class="form-group has-feedback {{ $errors->has('starting_receipt') ? 'has-error' : '' }}">
                                                <input type="text" name="starting_receipt" class="form-control" value="{{ old('starting_receipt')?: 1 }}"
                                                       placeholder="Enter Starting Receipt">
                                                @if ($errors->has('starting_receipt'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('starting_receipt') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="m-t-20 m-b-20">
                            <div class="row">
                                <div class="col-lg-4"></div>
                                <div class="col-lg-4">
                                    <button type="submit" class="btn btn-block btn-success">
                                        Submit
                                    </button>
                                </div>
                                <div class="col-lg-4"></div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <!-- /.modal-content -->
</div>
@endsection
