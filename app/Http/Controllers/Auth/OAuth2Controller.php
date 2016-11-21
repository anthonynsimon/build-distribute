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

        // We need an email to associate to this user
        $email = $providerUser->email;
        if (!$email) {
            abort(400);
        }

        // Handle case when user already has a social profile
        // If we already have records of this social profile, try to get the associated user
        $existingSocialUser = SocialiteUser::where('social_id', '=', $providerUser->id)->where('provider', '=', $provider)->first();
        if (!empty($existingSocialUser)) {
            $existingUser = $existingSocialUser->user;
            // Only attempt to login if associated user was found
            Auth::login($existingUser, true);
            return redirect()->to('/projects');
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

        // Associate the social profile the retrieved or newly created user
        $newSocialUser = new SocialiteUser;
        $newSocialUser->social_id = $providerUser->id;
        $newSocialUser->provider = $provider;
        $existingUser->social()->save($newSocialUser);

        Auth::login($existingUser, true);
        return redirect()->to('/projects');
    }
}