<?php

namespace App\Http\Controllers;

use Request;
use App\Todo;
use App\Task;
use App\Http\Resources\Task as TaskResource;
use  Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        // Get the todos
        $todos = Todo::where('task_id',$id)->get();
        $current_task = Task::where('id',$id)->firstOrFail();

        $progress = taskProgress($current_task->id);
        $current_task = $current_task->toArray();
        $current_task['complete'] = $progress[0];
        $current_task['count']= $progress[1];
        
        // Return collection of todos as a resource
        return (TaskResource::collection($todos))->additional(['meta' => [
                    'current_task' => $current_task
                ]]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {

        $validation = Validator::make(Request::all(),[ 
        'name' => 'required|string'
        ]);

        if($validation->fails()){

            $errors = $validation->errors();
            return $errors->toJson();

        } else{
                $newTodo = Todo::create([
                    'name' => Request::get('name'),
                    'complete'=>0
                ]);

                $newTodo->task()->associate($id)->save();
                // update task status
                $task = Task::where('id',$id)->firstOrFail();



                if($task->status==2){
                    $task->status = 1;
                    $task->save();
                }

                $progress = taskProgress($task->id);
                $task = $task->toArray();
                $task['complete'] = $progress[0];
                $task['count']= $progress[1];

                $todos = Todo::where('task_id',$id)->get();

                return TaskResource::collection($todos)->additional(['meta' => [
                    'current_task' => (object)$task,
                ]]); ;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
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
         $validation = Validator::make(Request::all(),[ 
        'name' => 'required|string'
        ]);

        if($validation->fails()){

            $errors = $validation->errors();
            return $errors->toJson();

        } else{
                $todo = Todo::where('id',$id)->firstOrFail();
                $todo->update([
                    'name' => Request::get('name')
                ]);

                return new TaskResource($todo);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $todo = Todo::where('id',$id)->firstOrFail();
        $task = $todo->task;
        $todo->delete();

        $progress = taskProgress($task->id);
        $complete_items = $progress[0];
        $all_items = $progress[1];
        if($complete_items == $all_items){
            // status = 2 = complete
            $task->status = 2;
        }
        elseif ($complete_items>0) {
            // status = 1 = started / in progress
            $task->status = 1;
        }
        elseif ($complete_items==0) {
            $task->status = 0; // not started
        }
        else{
           //
        }
        $task->save();

        $messages = new MessageBag;
        $messages->add('success','Item deleted successfully.');
        return $messages->toJson();
    }

    public function toggleComplete(Request $request,$id)
    {
        $todo = Todo::where('id',$id)->firstOrFail();
        $todo->complete = !($todo->complete);
        $todo->save();
        // get task and update status
        $task = $todo->task;
        $progress = taskProgress($task->id);
        $complete_items = $progress[0];
        $all_items = $progress[1];
        if($complete_items == $all_items){
            // status = 2 = complete
            $task->status = 2;
        }
        elseif ($complete_items>0) {
            // status = 1 = started / in progress
            $task->status = 1;
        }
        elseif ($complete_items==0) {
            $task->status = 0; // not started
        }
        else{
           //
        }
        $task->save();

                $task = $task->toArray();
                $task['complete'] = $complete_items;
                $task['count']= $all_items;

        return new TaskResource($task);
    }
}
