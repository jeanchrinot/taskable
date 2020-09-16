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
	    	$users = User::paginate($this->pagination);
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
}
