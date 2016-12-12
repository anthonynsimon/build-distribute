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
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

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

    private function notifyRegistration($user)
    {
        $env = Config::get('app.env');
        if ($env != 'production') {
            return;
        }

        $fromAddr = Config::get('mail.from.address');
        $fromName = Config::get('mail.from.name');
        $toAddr = explode(',', Config::get('mail.to'));

        // Return early if missing email from and to addresses
        if (empty($fromAddr) || empty($toAddr)) {
            return;
        }

        $subject = 'New user registered!';
        $data =  ['user' => $user];

        Mail::send('emails.signinNotification', $data, function ($message) use ($fromAddr, $fromName, $toAddr, $subject) {
            $message->from($fromAddr, $fromName);
            $message->to($toAddr);
            $message->subject($subject);
        });
    }
}
