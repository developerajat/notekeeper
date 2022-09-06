@extends('layouts.auth')
@section('content')
    <div class="col-md-6 offset-md-3 login-body">
        <div class="card my-5">
            <form action="{{ route('password.reset', request()->id) }}" method="POST" class="card-body cardbody-color p-lg-5">
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

                <div class="mb-3">
                    <input type="password" name="password" class="form-control" id="password" placeholder="password"
                        required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password_confirmation" class="form-control" id="password_confirm" placeholder="confirm password"
                        required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-color px-5 mb-5 w-100 sendBtn">Submit</button>
                </div>

            </form>
        </div>
    </div>
@endsection
@section('extra_scripts')
    <script>
        $(document).ready(function() {
            $(document).on('submit','form',function(){
                $('.sendBtn').addClass('disabled');
            });
        });
    </script>
@endsection
