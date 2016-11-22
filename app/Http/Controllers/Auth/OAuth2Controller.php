<?php

namespace App\Http\Controllers\Auth;

use Config;
use Auth;
use App\User;
use App\SocialiteUser;
use App\Http\Controllers\Auth\AuthController;
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

    protected function handleProviderCallback($provider, Request $request, AuthController $authController) {
        if ($request->input('denied') != '' || $request->input('error') != '') {
            return redirect()->to('/login')->withErrors(['oauth2_message' => 'Could not login. Please use another method.']);
        }

        $providerUser = Socialize::with($provider)->user();

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
            $name = empty($providerUser->name) ? 'Unknown' : $providerUser->name;
            $existingUser = $authController->create([
                'name' => $name,
                'email' => $email,
                'password' => str_random(16),
            ]);
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