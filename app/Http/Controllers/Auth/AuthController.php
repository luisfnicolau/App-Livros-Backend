<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Managers\SocialLogin;
use App\User;
use Socialite;

class AuthController extends Controller
{
    /**
     * Redirect the user to the given driver authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($driver="facebook")
    {
        return Socialite::driver($driver)->redirect();
    }

    /**
     * Obtain the user information from given driver.
     *
     * @return Response
     */
    public function handleProviderCallback($driver="facebook")
    {
        //TODO if using other login providers, change it here
        if(!in_array($driver, [
            'facebook',
            'google'
        ])){
            abort(403);
        }

        $socialiteUser = Socialite::driver($driver)->user();

        Auth::login(SocialLogin::findOrCreateUser($socialiteUser, $driver));

        //after login redirecting to home page
        return redirect(route('user.home'));
    }
}
