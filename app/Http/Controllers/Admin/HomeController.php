<?php
namespace App\Http\Controllers\Admin;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends AdminBaseController
{
    public function index()
    {
        return redirect(route('admin.home'));
    }

    public function showHome(){

        return view('admin.home');
    }

    public function getChangePassword(){
        return view('admin.change-password');
    }

    public function postChangePassword(Request $request){
        $admin = Auth::guard('admin')->user();

        if(!Hash::check($request->get('current_password'), $admin->password)){
            abort(403);
        }
        
        $validator = Validator::make($request->all(), [
            'new_password' => 'bail|required|confirmed|min:6|different:current_password',
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        $admin->password = bcrypt($request->get('new_password'));
        if($admin->save()){
            return view('admin.change-password')->with([
                                                    'info'=>'Senha Alterada'
                                                    ]);
        }
        abort(500);
        return "";
    }
}
