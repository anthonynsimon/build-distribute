<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Config;
use App\User;
use App\SocialiteUser;
use App\Http\Controllers\Controller;
use Socialize;
use Illuminate\Http\Request;


class OAuth2Controller extends Controller
{
    protected function redirectToProvider($provider) {
        $providerKey = Config::get('services.' . $provider);
        if (empty($providerKey)) {
            // Provider not known
            abort(500);
        }
        return Socialize::with($provider)->redirect();
    }

    protected function handleProviderCallback($provider, Request $request) {
        if ($request->input('denied') != '') {
            return redirect()->to('/login')->with('message', 'You did not share your profile data with our social app.');
        }

        $providerUser = Socialize::with($provider)->user();

        // We need an email to associate to this user
        // TODO: handle social profile id match instead of email
        $email = $providerUser->email;
        if (!$email) {
            abort(400);
        }

        // If a user hasn't been registered yet with this email, create one
        $existingUser = User::where('email', '=', $providerUser->email)->first();
        if (empty($existingUser)) {
            $newUser = new User;
            $newUser->email = $email;
            $newUser->name = $providerUser->name;

            $newUser->password = bcrypt(str_random(16));
            $newUser->remember_token = str_random(64);
            $newUser->save();

            $existingUser = $newUser;
        }

        // Associate the created user with this social profile
        $newSocialUser = new SocialiteUser;
        $newSocialUser->social_id = $providerUser->id;
        $newSocialUser->provider = $provider;
        $existingUser->social()->save($newSocialUser);

        Auth::login($existingUser, true);
        return redirect()->to('/projects');
    }
}