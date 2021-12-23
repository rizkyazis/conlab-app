<?php

namespace App\Http\Controllers\Dashboard;

use App\Accounts;
use App\Courses;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        $users = User::all();
        return view('dashboard.user.index')->with('users',$users);
    }

    public function roles($id, Request $request){
        try{
            $user = User::findOrFail($id);
        }catch(ModelNotFoundException $ex){
            toast('You can\'t do that!', 'error');
            return redirect()->back()->with('error','You can\'t do that!');
        }

        $user->role = $request->role;
        $user->save();

        toast('Roles changed', 'success');
        return redirect()->back();
    }

    public function user_search(Request $request){
        $accounts = Accounts::where('fullname', 'like', '%' . $request->query('name') . '%')->get();
        $userFind = User::where('username', 'like', '%' . $request->query('name') . '%')->get();

        $id = array();

        foreach ($accounts as $account){
            $data = ['id','=',$account->user_id];
            array_push($data,'or');
            array_push($id,$data);
        }

        foreach ($userFind as $user){
            $data = ['id','=',$user->id];
            array_push($data,'or');
            if (!in_array($data,$id)){
                array_push($id,$data);
            }
        }

        $this->array_flatten($id);

        $users = User::where($id);

        if ($users->count() === 0) {
            toast('User not found', 'error');
            return redirect()->back();
        }
        return view('dashboard.user.index')
            ->with('users', $users->get());
    }

    private function array_flatten($array)
    {
        if (!is_array($array)) {
            return false;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, $this->array_flatten($value));
            } else {
                $result = array_merge($result, array($key => $value));
            }
        }
        return $result;
    }
}
