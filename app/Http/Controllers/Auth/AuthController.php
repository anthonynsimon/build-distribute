<?php

namespace App\Http\Controllers\Auth;

use Config;
use Mail;
use App\Role;
use App\Permission;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
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
            'name' => 'required|max:255',
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
    public function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
		
		$externalRole = Role::where('name', '=', 'externalUser')->first();
		
		if ($externalRole) {
			$user->role_id = $externalRole->id;
		}

        $this->notifyRegistration($user);
		
		return $user;
    }

    private function notifyRegistration($user) {
        $fromAddr = Config::get('mail.from.address');
        $fromName = Config::get('mail.from.name');
        $toAddr = Config::get('mail.to');

        // Return early if missing email from and to addresses
        if (empty($fromAddr) || empty($toAddr)) {
            return;
        }

        $subject = 'New user registered!';
        $data =  ['user' => $user];

        Mail::send('emails.signinNotification', $data, function ($message) use($fromAddr, $fromName, $toAddr, $subject) {
            $message->from($fromAddr, $fromName);
            $message->to($toAddr)->subject($subject);
        });
    }
}
