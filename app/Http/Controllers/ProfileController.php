<?php

namespace App\Http\Controllers;

use App\Accounts;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    public function index(){
        $account = Accounts::with('user')->where('user_id', auth()->user()->id);
        return view('profile.index')
            ->with('account', $account->first());
    }

    public function update(Request $request){
        $rules = [
            'fullname' => 'required|max:150',
            'birth_place' => 'required|max:150',
            'birth_date' => 'required|date',
            'contact'=> 'required',
            'address' => 'required|max:240',
            'photo' => 'mimes:jpg,png,jpeg|max:5120'
        ];

        $this->validate($request, $rules);

        $account = Accounts::where('user_id', auth()->user()->id)->first();

        if($request->photo != null){
            $photo = Storage::put('public/profile', $request->file('photo'));
        }else{
            $photo = $account->photo;
        }

        $account->update([
            'fullname' => ucwords($request->fullname),
            'birth_place'=>ucwords($request->birth_place),
            'birth_date'=>$request->birth_date,
            'contact' => $request->contact,
            'address' => $request->address,
            'photo' => $photo
        ]);

        toast('Account updated!', 'success');
        return redirect()->back();
    }

    public function password_update(Request $request){
        $rules = [
            'old_password' => 'required',
            'password' => 'required|confirmed|max:150|min:8'
        ];

        $this->validate($request, $rules);

        $user = User::find(auth()->user()->id);

        if (Hash::check($request->old_password, $user->password)) {
            $user->fill([
                'password' => Hash::make($request->password)
            ])->save();

            toast('Password Changed', 'success');
            return redirect()->back();
        } else {
            return redirect()->back()->withErrors([
                'old_password' => 'Password did not match'
            ]);
        }
    }
}
