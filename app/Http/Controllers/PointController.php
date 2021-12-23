<?php

namespace App\Http\Controllers;

use App\Accounts;
use App\Point;
use App\User;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Object_;

class PointController extends Controller
{
    public function index(){
        $users = User::where('role','student')->get();

        $points = Point::all();

        $accountPoint = array();

        foreach ($users as $user){
            $account = Accounts::where('user_id',$user->id)->first();
            $totalPoint = 0;
            foreach ($points as $point){
                if ($account->id === $point->account_id){
                    $totalPoint = $totalPoint + $point->point;
                }
            }
            if ($account->fullname != ''){
                array_push($accountPoint,[
                    'id'=>$account->id,
                    'user_id'=>$account->user_id,
                    'name'=>$account->fullname,
                    'point'=>$totalPoint
                ]);
            }
        }

        $rankings = (object) collect($accountPoint)->sortByDesc('point');

        return view('point.index')
            ->with('rankings',$rankings);
    }


}

