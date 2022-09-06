@extends('layouts.auth')
@section('content')
    <div class="col-md-6 offset-md-3 login-body">
        <div class="card my-5">
            <form id="verifyOTP" action="{{ route('verify.otp', [base64_encode($user->id)]) }}" method="POST"
                class="card-body cardbody-color p-lg-5 digit-group" data-group-name="digits" autosubmit="false">
                @csrf
                @if ($errors->any())
                    <div class="signup_errors">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="kabutar text-center">
                    <img src="https://cdn.pixabay.com/photo/2016/03/31/19/56/avatar-1295397__340.png"
                        class="img-fluid profile-image-pic img-thumbnail rounded-circle my-3" width="200px" alt="profile">
                </div>
                <div class="mb-3 text-center">
                    <input type="text" id="digit-1" name="otp[]" data-next="digit-2" autofocus pattern="[0-9]*" inputmode="numeric" />
                    <input type="text" id="digit-2" name="otp[]" data-next="digit-3" data-previous="digit-1" pattern="[0-9]*" inputmode="numeric" />
                    <span class="splitter">&ndash;</span>
                    <input type="text" id="digit-3" name="otp[]" data-next="digit-4" data-previous="digit-2" pattern="[0-9]*" inputmode="numeric" />
                    <input type="text" id="digit-4" name="otp[]" data-next="digit-5" data-previous="digit-3" pattern="[0-9]*" inputmode="numeric" />
                </div>

                <div class="text-center">
                    <input type="hidden" name="previous" value="{{ request()->p ?? null }}">
                    <button type="submit" class="btn btn-color px-5 mb-5 w-100">Submit</button>
                </div>
                <div class="text-center btn-outer tb-space resendOtp">
                    <a href="{{ route('verify.otp.resend', ['email' => $user->email]) }}"
                        class="btn mt-20 resendBtn">Resend</a>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('extra_scripts')
    <script>
        $(document).ready(function() {
            $('.digit-group').find('input').each(function() {
                $(this).attr('maxlength', 1);
                $(this).on('keyup', function(e) {
                    var parent = $($(this).parent());

                    if (e.keyCode === 8 || e.keyCode === 37) {
                        var prev = parent.find('input#' + $(this).data('previous'));

                        if (prev.length) {
                            $(prev).select();
                        }
                    } else if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e
                            .keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e
                        .keyCode === 39) {
                        var next = parent.find('input#' + $(this).data('next'));

                        if (next.length) {
                            $(next).select();
                        } else {
                            console.log(parent);
                            $("#verifyOTP").submit()
                        }
                    }
                });
            });

            $('.resendBtn').on('click', function() {
                $('.resendBtn').addClass('disabled');
            });
        });
    </script>
@endsection
