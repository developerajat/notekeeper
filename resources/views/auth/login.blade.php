@extends('layouts.auth')
@section('content')
    <div class="col-md-6 offset-md-3 login-body">
        <div class="card my-5">
            <form action="{{ route('login') }}" method="POST" class="card-body cardbody-color p-lg-5">
                @csrf
                <div class="kabutar text-center">
                    <img src="https://cdn.pixabay.com/photo/2016/03/31/19/56/avatar-1295397__340.png"
                        class="img-fluid profile-image-pic img-thumbnail rounded-circle my-3" width="200px" alt="profile">
                </div>
                <div class="mb-3">
                    <input name="email" type="email" class="form-control" id="email" aria-describedby="emailHelp"
                        placeholder="Enter Email" value="{{ old('email') }}" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" id="password" placeholder="password"
                        required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-color px-5 mb-5 w-100">Login</button>
                </div>
                <div class="login-page-links">
                    <a class="forgot-password d-block mt-3 mb-1" href="{{ route('forgetPasswordForm') }}">Forgot Password?</a>
                    <p class="mb-0 login-register">Didn't have an account?<a class="mx-1" href="{{ route('registerForm') }}">Register
                            Now</a></p>
                </div>
            </form>
        </div>
    </div>
@endsection
