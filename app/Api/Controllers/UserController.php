<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function index(Request $request)
    {
            $message = '';
            $query = $request->get('q');
//            $password = $request->get('password');
//            $user = User::where('email', $email);
            $user = DB::table('users')
                ->where('email', $query)
                ->orwhere('id', $query)
                ->first();

            if(!$user){
                $message = 'user not found';
                return response()->json(array('message' => $message));
            }
//            if(md5($password) != $user->get('password')){
//            if($password != $user->password){
//                $message = 'incorrect password';
//                return response()->json(array('message' => $message));
//            }
            $user->password = null;
            $message = 'success';
            return response()->json(array('message' => $message, 'user' => $user));
    }

    public function store(Request $request)
    {
            $message = '';
           if($request->has('flag') && $request->get('flag') == 'change_password'){
                return $this->changePassword($request);
           }

           if($request->has('flag') && $request->get('flag') == 'facebook'){
                return $this->facebookLoginOrRegister($request);
           }

           if ($request->has('flag') && $request->get('flag') == 'update') {
             return $this->updateField($request);
           }

            $email = $request->get('email');
            $user = User::where('email', $email)->first();

            if($user){
                if($request->has('password') && ($request->get('password') == $user->password)){
                   $message = 'success';
                } else {
                    $message = 'password invalid';
                }
            } else if($request->get('register')){
                return $this->registerUser($request);
            } else {
                $message = 'user not found';
            }

            return response()->json(array('message' => $message, 'user' => $user));

    }

    public function registerUser($request)
    {
      $name = $request->get('name');
      $facebook_id = $request->get('facebook_id');
      $google_id = $request->get('google_id');
//                $userStatus = 1;
//            if(md5($password) != $user->get('password')){
      $password = $request->get('password');
      $email = $request->get('email');

      $user = new User();
      $user->name = $name;
      $user->facebook_id = $facebook_id;
      $user->google_id = $google_id;
//                $user->userStatus = $userStatus;
      $user->email = $email;
      $user->password = $password;

      if($user->save())
      {
          $message = 'success';
      } else {
          $message = 'fail';
      }
      return response()->json(array('message' => $message, 'user' => $user));

    }

    public function userExist(Request $request)
    {
        $message = '';
            $email = $request->get('email');
            $user = User::where('email', $email)->first();

            if($user){
                $message = 'user exist';
                return response()->json(array('message' => $message));
            }
    }

    public function changePassword($request)
    {
      $newPassword = $request->get('new_password');
      $oldPassword = $request->get('old_password');
      $userId = $request->get('user_id');

      $user = User::where('id', $userId)
              ->first();

      if($oldPassword == $user->password){
          $user->password = $newPassword;
          if($user->save())
          {
              return response()->json(array('message' => 'sucess'));
          }
          else
          {
              return response()->json(array('message' => 'error when save'));
          }
      } else {
          return response()->json(array('message' => 'password incorrect'));
      }
    }

    public function facebookLoginOrRegister($request)
    {
      $message = '';
      $email = $request->get('email');

      $user = User::where('email', $email)
              ->first();

      if($user){
          $message = 'success';
          return response()->json(array('message' => $message, 'user' => $user));
      } else {
          $name = $request->get('name');
          // $facebook_id = $request->get('facebook_id');
          // $google_id = $request->get('google_id');
//                  $userStatus = 1;
          // $password = $request->get('password');

          $user = new User();
          $user->name = $name;
          // $user->facebook_id = $facebook_id;
          // $user->google_id = $google_id;
//                  $user->userStatus = $userStatus;
          $user->email = $email;

          if($user->save())
            $message = 'success';

          return response()->json(array('message' => $message, 'user' => $user));

      }
    }

    public function updateField(Request $request) {
      $userId = $request->id;
      $field = $request->field;
      $value = $request->value;

      $user = User::where('id', $userId)
              ->first();

      if ($user) {

        $affected = DB::update('update users set '.$field.' = '.$value.' where id = ?', [$userId]);

        if ($affected) {
          return response()->json(array('message' => 'sucess', 'user' => User::where('id', $userId)
                  ->first()));
        } else {
          return response()->json(array('message' => 'error when updating'));
        }
    }
  }

}
