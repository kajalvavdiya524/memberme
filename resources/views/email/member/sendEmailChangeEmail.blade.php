<html>
<body>
<p>
    Dear {{$member->first_name/*. ' '. $member->last_name*/}}, <br>
    You have requested to change email on this address. please <a href="{{route('memberEmailChangeVerification',$token)}}">Click Here</a> to update your email address. <br>
    Please ignore this email if you have not requested this. <br> 
    Thanks. <br>
    <br>
    Team@memberme.me
</p>
</body>
</html>