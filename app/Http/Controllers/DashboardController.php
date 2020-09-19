<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Task;
use App\Todo;
use App\Http\Resources\Task as TaskResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use App\User;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }

    
    public function index()
    {
        
        return view('dashboard');
        
    }

    public function users()
    {
        return view('users');
    }

    public function profile()
    {
        return view('profile');
    }
}
