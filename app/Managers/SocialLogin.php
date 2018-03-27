<?php
namespace App\Managers;

use App\User;
use Laravel\Socialite\AbstractUser as SocialiteUser;

class SocialLogin
{

    /**
     * Finds or creates user based on given SocialiteUser and the oauth driver
     *
     * @param \Laravel\Socialite\AbstractUser $socialiteUser
     * @param string $driver
     * 
     * @return \App\User
     */
    public static function findOrCreateUser(
                                            SocialiteUser $socialiteUser,
                                            string $driver)
    {
        $driver_id = $driver.'_id';
        $driver_token = $driver.'_token';
        $userInfo = self::getUserInfo($socialiteUser, $driver);

        $user = User::where($driver_id, $socialiteUser->id)->first();
        if (!self::userExists($user)) {
            $user = User::where('email', $socialiteUser->email)->first();
        }
        else if (!self::userExists($user)) {
            $user = new User();
        }
        if (!empty($userInfo)) {
            $user->fill($userInfo);
            $user->save();
        }
        return $user;
    }

    private static function userExists(User $user)
    {
        return $user && $user->exists;
    }

    private static function getUserInfo(SocialiteUser $user, string $driver)
    {
        $driver_id = $driver.'_id';
        $driver_token = $driver.'_token';
        $info = [];

        if(!empty($socialiteUser->name))
            $info['name'] = $socialiteUser->name;
        if(!empty($socialiteUser->email))
            $info['email'] = $socialiteUser->email;
        if(!empty($socialiteUser->token))
            $info[$driver_token] = $socialiteUser->token;
        if(!empty($socialiteUser->id))
            $info[$driver_id] = $socialiteUser->id;
        if(!empty($socialiteUser->avata))
            $info['avatar'] = $socialiteUser->avata;

    }
}
