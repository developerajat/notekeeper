@component('mail::message')
# Hi,
Welcome to AK Notes.

Your OTP for email verification is {{$otp ?? ''}}.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
