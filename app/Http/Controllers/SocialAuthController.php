<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    //
    public function getFbAuth(){
        return Socialite::driver('facebook')->redirect();
    }

    public function getFbAuthCallback(){
        $user = Socialite::driver('facebook')->user();

        $user = (User::whereEmail($user->getEmail())->first())
            ?: User::create([
                'name'=>$user->getName(),
                'email'=>$user->getEmail(),
            ]);

        auth()->login($user, true);

        return redirect(route('home'));
    }

    public function getGoogleAuth(){
        return Socialite::driver('google')->redirect();
    }

    public function getGoogleAuthCallback(){
        $user = Socialite::driver('google')->user();

        $user = (User::whereEmail($user->getEmail())->first())
            ?: User::create([
                'name'=>$user->getName(),
                'email'=>$user->getEmail(),
            ]);

        auth()->login($user, true);

        return redirect(route('home'));
    }
}
