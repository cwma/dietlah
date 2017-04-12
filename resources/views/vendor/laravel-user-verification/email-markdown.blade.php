@component('mail::message')
# Greetings!

You are receiving this email because we received a new account registration at <a href="https://dietlah.sg">dietlah.sg</a> for your email address.

@component('mail::button', ['url' => route('email-verification.check', $user->verification_token) . '?email=' . urlencode($user->email) ])
Click here to verify your account
@endcomponent


If you did not register an account with us, no further action is required.

Regards,<br>
{{ config('app.name') }}

@component('mail::subcopy')
If you're having trouble clicking the "Click here to verify your account" button, copy and paste the URL below
into your web browser: [{{ route('email-verification.check', $user->verification_token) . '?email=' . urlencode($user->email)}}]({{ route('email-verification.check', $user->verification_token) . '?email=' . urlencode($user->email) }})
@endcomponent

@endcomponent