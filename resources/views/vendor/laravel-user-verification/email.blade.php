<html>
Greetings,<br>
<br>
You are receiving this email because we received a new account registration at dietlah.sg at this email address.<br>
<br>
Click here to verify your account: <a href="{{ $link = route('email-verification.check', $user->verification_token) . '?email=' . urlencode($user->email) }}">{{ $link }}</a><br>
<br>
If you did not register an account with us, no further action is required.<br>
<br>
Regards,<br>
DietLah!
</html>