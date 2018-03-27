<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Model\Pricing;
use App\Notifications\UserPricingUpdated;
use Illuminate\Http\Request;
use Validator;

class UsersController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->get('q');
        if($query==""){
            $users = User::paginate(15);
        }else if(strpos($query, "@") !== false){
            $users = User::where('email', $query)
                            ->orWhere('email', 'like', "%$query%")
                            ->orWhere('name', 'like', "%$query%")
                            ->paginate(15);
        }else{
            $users = User::where('name', 'like', "%$query%")
                            ->orWhere('email', 'like', "%$query%")
                            ->paginate(15);
        }
        return view('admin.user.list')->with([
                                             'users'=> $users]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $pricings = Pricing::all();
        return view('admin.user.edit')->with([
                                             'user' => $user,
                                             'pricings' => $pricings
                                             ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validator = Validator::make($request->all(), [
                        'name'=> 'min:3',
                        'email'=> 'email',
                        'pricing_id'=> 'bail|integer|min:1|exists:pricings,id',
                     ]);


        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        if($request->has('pricing_id')) {
            $previous_pricing = $user->pricing;
            $user->pricing_id = $request->get('pricing_id');
            $user->fill($request->all());
            if($user->save()){
                $user->notify(new UserPricingUpdated(
                                                     $user,
                                                     $previous_pricing
                                                     ));
            }
        } else {
            $user->fill($request->all());
            $user->save();
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()
                ->back()
                ->with('info', "Usuário $user->name deletado.");
    }
}
