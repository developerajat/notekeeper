@component('mail::message')
# Hi,

Your OTP to reset password is {{$otp ?? ''}}.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
