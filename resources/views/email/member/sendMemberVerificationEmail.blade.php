Dear {{$member->first_name. ' ' . $member->last_name }},<br> <br>
{{$organization->name}} use memberme to manage their membership. <br>
As a member of {{$organization->name}} you are now able to use memberme to view your virtual membership card, points and vouchers. <br>
Before login please <a href="{{env('APP_URL').'/api/members/verify/'.$member->verify_token}}">Click here</a> to verify your account. <br>
<br>
<br>
For any questions please contact {{$organization->name}} <br>
<br>
Enjoy<br>
<br>
The team @ memberme<br>