<?php

namespace App\Http\Controllers;

use App\Mail\VerifyOtp;
use App\Mail\ForgotPasswordOtp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            if (!empty($request->next)) {
                return redirect($request->next);
            }
            notify()->success('Logged in successfully');
            return redirect(route('index'));
        }
        notify()->warning('Invalid credentials');
        return redirect(route('loginForm'))->withInput($request->only('email'));
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'email'             => 'required|email:rfc,dns|unique:users,email',
            'password'          => 'required|min:8',
            'password_confirmation' => 'required|same:password'
        ]);

        $userData = [];
        $userData['otp'] = $otp = mt_rand(1000, 9999);
        $userData['password'] = Hash::make($request->password);
        $userData['name'] = substr($request->email, 0, strrpos($request->email, '@'));

        $user = User::updateOrCreate([
            'email' => $request->email,
        ], $userData);

        Mail::to($user->email)->send(new VerifyOtp($otp));

        notify()->success(__('OTP successfully sent on email'));
        return redirect()->route('verificationScreen', base64_encode($user->id));
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::logout();
            session()->flush();
            notify()->success('Logged out successfully');
            return redirect(route('loginForm'));
        }

        notify()->success('Something went wrong');
        return back();
    }

    public function forgetPasswordForm()
    {
        return view('auth.forgot');
    }

    public function forgetPassword(Request $request)
    {
        $this->validate($request, [
            'email'             => 'required|exists:users,email',
        ]);

        $otp = mt_rand(1000, 9999);

        $user = User::where('email', $request->email)->first();
        $user->update([
            'otp' => $otp
        ]);

        $tokenData = DB::table('password_resets')->where('email', $request->email)->first();

        if (!$tokenData) {
            DB::table('password_resets')->insert([
                'email' => strtolower($request->email),
                'token' => \Str::random(60),
                'created_at' => Carbon::now(),
            ]);

            $tokenData = DB::table('password_resets')->where('email', $request->email)->first();
        }

        Mail::to($user->email)->send(new ForgotPasswordOtp($otp));

        notify()->success(__('OTP successfully sent on email'));
        return redirect()->route('verificationScreen', [base64_encode($user->id), 'p' => $tokenData->token]);
    }

    public function verifyOtpForm(Request $request, $userId)
    {
        $user = User::find(base64_decode($userId));
        if ($user) {
            return view('auth.verify', ['user' => $user]);
        }
        notify()->success('Something went wrong');
        return back();
    }

    public function resendVerificationOtp(Request $request)
    {
        $request->validate([
            'email' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $otp = mt_rand(1000, 9999);
            $user->update(['otp' => $otp]);

            Mail::to($user->email)->send(new VerifyOtp($otp));

            notify()->success(__('OTP Successfully Sent'));
            return back()->withInput();
        }

        notify()->error(__("Something went wrong!"));
        return back()->withInput();
    }

    public function verifyOtp(Request $request, $id)
    {
        $user = User::find(base64_decode($id));

        $request->validate([
            'otp' => 'required',
        ]);

        $otp = implode('', $request->otp);

        if (isset($request->previous) && $user && $user->otp == $otp) {
            $tokenData = DB::table('password_resets')->where(['token' => $request->previous, 'email' => $user->email])->first();
            if (!$tokenData) {
                notify()->warning('Your link has been expired , please send new link');
                return redirect()->route('loginForm');
            }

            return redirect()->route('password.resetForm', $id);

        }
        if ($user && $user->email_verified_at) {
            notify()->success(__('Email already verified Please login'));
            return redirect(route('loginForm'));
        }

        if ($user && $user->otp == $otp) {
            $user->update([
                'email_verified_at' => date('Y-m-d H:i:s'),
                'otp' => null
            ]);

            Auth::loginUsingId($user->id);
            notify()->success(__('Email verified successfully'));
            return redirect(route('index'));
        }

        notify()->error(__("Incorrect OTP"));
        return back()->withInput();
    }

    public function resetPasswordForm($id)
    {
        return view('auth.reset_password', ['id' => $id]);
    }


    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password'              => 'required|min:8',
            'password_confirmation' => 'required|same:password'
        ]);

        $user = User::findOrFail(base64_decode($id));

        if ($user) {
            $user->update(['password' => Hash::make($request->password), 'otp' => null]);

            notify()->success(__("Password reset successfully."));
            return redirect()->route('loginForm');
        }

        notify()->error(__("Something went wrong."));
        return back();
    }


}
