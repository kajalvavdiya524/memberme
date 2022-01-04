
Hi {{$org->name}},<br>
<br>
Thank you for registering.<br>
To activate your account please verify it here <a href="{{route('sendEmailDone',['email' => $user->email , 'verifyToken' => $user->verify_token])}}"> Click here </a>.<br>
<br>
If you need assistance setting up {{$org->name}} on memberme please contact us at support@validate.co.nz<br>
<br>
<br>
Regards,<br>
<br>
The team @ memberme powered by Validate.<br>



