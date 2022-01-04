Dear {{$member->first_name. ' ' . $member->last_name }},<br> <br>
    {{$organization->name}} use memberme to manage their membership. <br>
As a member of {{$organization->name}} you are now able to use memberme to view your virtual membership card, points and vouchers. <br>

@php
    $firstMember = \App\Member::whereEmail($member->email)->first();
@endphp
@if(!empty($firstMember) && $firstMember->verify != \App\base\IStatus::ACTIVE)
    Before login please <a href="{{env('APP_URL').'/api/members/verify/'.$member->verify_token}}">Click here</a> to verify your account. <br>
@endif

To login use your email and this password: <b>{{$password}}</b> <br>
The password is valid for 48 hours, after this you will need to use the forgot password feature.<br>
<br>
<br>
For any questions please contact {{$organization->name}} <br>
<br>
Enjoy<br>
<br>
The team @ memberme<br>