<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;

class TestController extends Controller
{
    public function index()
    {
    		$task = Task::create([
        		'name'=>'My shopping list ',
        		'priority'=>1,
        		'status'=>0,
        		'start_date'=>now(),
        		'end_date'=> date_create("2020-12-31 00:00:00")
        	]);

        	$task->user()->associate(1)->save();

        	dd($task->id);
    }
}
