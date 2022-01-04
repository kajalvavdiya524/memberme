    . . . {{--
Dear User,<br>
<br>

You have been granted access to {{$organization->name}}<br>
To accept this invitation please <a href="{{env('APP_URL').'/api/verify/user/'.$verifyDetails->verify_token}}">Click here</a><br>
@if(!\App\User::whereEmail($verifyDetails->email)->first())<br>
Please login with your email and this Password: <b>{{$verifyDetails->data->password}} </b><br>
@endif

If this email was sent in error or you do not want to access this organization please ignore this email.<br>

<br>
<br>
Regards,<br>
<br>
The team @ memberme powered by Validate.<br>
--}}
