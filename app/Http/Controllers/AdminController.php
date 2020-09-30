<?php

namespace App\Http\Controllers;

use Request;
use App\Task;
use App\Todo;
use App\Http\Resources\Task as TaskResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\User;

class AdminController extends Controller
{   
    function __construct()
    {
    	$this->middleware('auth:api');
    }

    private $pagination = 10;

    public function users(Request $request)
    {
    	if (Auth::user()->hasRole('admin')) {

            $users = User::query();

             if (request()->status) {
                // Filter by status
                $status = $this->getStatus(request()->status);
                $users = $users->where([$status[0]=>$status[1]]);
            }

            if (request()->sort) {
               if (request()->sort=='asc') {
                   $users->orderBy('created_at');
               }
               else{
                   $users->orderBy('created_at','desc');
               }
            }
            else{
                $users->orderBy('created_at','desc');
            }

             // Search 
            if(request()->search) {
                $keyword = request()->search;
                $users = $users->where(function ($query) use($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                })->paginate($this->pagination);
            }
            else{
                $users = $users->paginate($this->pagination);
            }

	    	return TaskResource::collection($users);
    	}

    	return response()->json([
                'errors' => [
                	'auth'=>'Not Authorized'
                ]
            ],401);
    }

    public function user($id)
    {
        if (Auth::user()->hasRole('admin')) {
            $user = User::findORFail($id);
            return new TaskResource($user);
        }

        return response()->json([
                'errors' => [
                    'auth'=>'Not Authorized'
                ]
            ],401);
    }

    public function ban($id)
    {
         if (Auth::user()->hasRole('admin')) {
            $user = User::findORFail($id);
            $user->banned = !$user->banned;

            if($user->save()){
                return response()->json([
                    'success' => true
                ]);
            }

            return response()->json([
                    'success' => false
                ]);
        }

        return response()->json([
            'success'=>false
        ],401);
        
    }

    private function getStatus($statusName)
    {
        $statusArr = [
         'not_activated' => ['status',0],
         'activated' => ['status',1],
         'not_banned' => ['banned',0],
         'banned' => ['banned',1]
        ];

        return $statusArr[$statusName];
    }
}
