<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Task;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function index()
    {
        if (Auth::user()->can('view-tasks')) {
            $user = Auth::user();
            $tasks = $user->tasks->all();
            return view('dashboard')->with(['user'=>$user,'tasks'=>$tasks]);
        }

        abort(403, 'Access denied');
    }

    public function list()
    {
        $pagination = 10;

        if (Auth::user()->can('view-tasks')) {
            $user = Auth::user();
            if($user->hasRole('admin')){

                $tasks = DB::table('tasks');
            }
            else{
                $tasks = $user->tasks();
            }

            if(request()->priority){
                if(request()->priority=='low'){
                    $tasks = $tasks->where('priority',1)->paginate($pagination);
                }
                elseif(request()->priority=='medium') {
                    $tasks = $tasks->where('priority',2)->paginate($pagination);
                }
                elseif (request()->priority=='high') {
                   $tasks = $tasks->where('priority',3)->paginate($pagination);
                }
                else{
                    $tasks = $tasks->paginate($pagination);
                }
            }
            elseif (request()->status) {
                 if(request()->status=='complete'){
                    $tasks = $tasks->where('status',2)->paginate($pagination);
                }
                elseif(request()->status=='not_started') {
                    $tasks = $tasks->where('status',0)->paginate($pagination);
                }
                elseif (request()->status=='in_progress') {
                   $tasks = $tasks->where('status',1)->paginate($pagination);
                }
                else{
                    $tasks = $tasks->paginate($pagination);
                }
            }
            else{
                $tasks = $tasks->paginate($pagination);
            }

            return view('tasks')->with(['tasks'=>$tasks]);
        }
        abort(403, 'Access denied');
    }
}
