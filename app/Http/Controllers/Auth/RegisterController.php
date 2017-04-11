<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;

use Jrean\UserVerification\Exceptions\UserNotFoundException;
use Jrean\UserVerification\Exceptions\UserIsVerifiedException;
use Jrean\UserVerification\Exceptions\TokenMismatchException;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    use VerifiesUsers;
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['getVerification', 'getVerificationError', 'resendVerify', 'resendVerifyPage', 'banned']]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|min:3|max:20|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
	$identicon = new \Identicon\Identicon();
	$imageDataUri = $identicon->getImageDataUri($data['username']);
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
	    'profile_pic' => $imageDataUri,
        ]);
    }

    public function register(Request $request) {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        event(new Registered($user));

        $this->guard()->login($user);

        UserVerification::generate($user);

        UserVerification::send($user, 'DietLah! - Please verify your email address');

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

    public function resendVerifyPage() {
        if (!Auth::check()) {
            return redirect("/");
        }
        if(Auth::user()->verified) {
            return redirect("/");
        }
        return view('vendor/laravel-user-verification/user-verification');
    }

    public function resendVerify() {
        if (!Auth::check()) {
            return redirect("/");
        }
        if(Auth::user()->verified) {
            abort(404);
        }
        $user = Auth::user();
        $now = Carbon::now();
        $last = $user->updated_at;
        $diff = $now->diffInMinutes($last);
        if($diff > 30) {
            UserVerification::generate($user);
            try {
                $response = UserVerification::send($user, 'DietLah! - Please verify your email address');
                return back()->with('status', "We've sent you another verification email.");
            } catch (\Exception $e) {
                $user->updated_at = $now->subMinutes(30);
                $user->save();
                return back()->withErrors(['email' => 'We were not able to send a reset link to your email address at this time.
                    Please try again later.']);
            }
        } else {
                return back()->withErrors(['email' => 'You can only request a maximum of 1 verification email every 30mins.']);
        }
    }

    public function getVerification(Request $request, $token)
    {
        if (! $this->validateRequest($request)) {
            return view('verification-failed');
        }

        try {
            UserVerification::process($request->input('email'), $token, $this->userTable());
        } catch (\Jrean\UserVerification\Exceptions\UserNotFoundException $e) {
            return view('vendor/laravel-user-verification/verification-failed');
        } catch (\Jrean\UserVerification\Exceptions\UserIsVerifiedException $e) {
            return view('vendor/laravel-user-verification/verification-passed');
        } catch (\Jrean\UserVerification\Exceptions\TokenMismatchException $e) {
            return view('vendor/laravel-user-verification/verification-failed');
        }

        if (Auth::check()) {
            return redirect('/');
        } else {
            return view('vendor/laravel-user-verification/verification-passed');
        }
    }

    public function banned() {
        if(Auth::check() && Auth::user()->banned) {
            return view("banned");
        }
        return redirect("/");
    }
}
