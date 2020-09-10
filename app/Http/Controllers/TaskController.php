<?php

namespace App\Http\Controllers;

use Request;
use App\Task;
use App\Todo;
use App\Http\Resources\Task as TaskResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        
            $tasks = Task::where('user_id',1);

            $pagination = 5;

            if (request()->priority && request()->status) {
                // Filter by priority and status
                $priority = $this->getPriority(request()->priority);
                $status = $this->getStatus(request()->status);

                if(is_numeric($priority) && is_numeric($status)){
                    $tasks = $tasks->where(['priority'=>$priority,'status'=>$status])->orderBy('id','desc');
                }
                else{
                    $tasks = $tasks->orderBy('id','desc');
                }

            }
            elseif(request()->priority){
                $priority = $this->getPriority(request()->priority);
                if(is_numeric($priority)){
                    $tasks = $tasks->where('priority',$priority)->orderBy('id','desc');
                }
                else{
                    $tasks = $tasks->orderBy('id','desc');
                }
            }
            elseif (request()->status) {
                $status = $this->getStatus(request()->status);

                if(is_numeric($status)){
                    $tasks = $tasks->where('status',$status)->orderBy('id','desc');
                }
                else{
                    $tasks = $tasks->orderBy('id','desc');
                }
            }
            else{
                $tasks = $tasks->orderBy('id','desc');
            }

            // Search 
            if(request()->search) {
                $keyword = request()->search;
                $tasks = $tasks->where(function ($query) use($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%')->orderBy('id','desc');
                })->paginate($pagination);
            }
            else{
                
                $tasks = $tasks->paginate($pagination);
            }

        return $this->getTaskTodoCollection($tasks);
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
    public function store(Request $request)
    {
        $validation = Validator::make(Request::all(),[ 
        'name' => 'required|string',
        'priority'=>'required|numeric|max:3|min:1',
        'start_date'=>'required|date',
        'end_date'=>'required|date'
        ]);

        if($validation->fails()){

            $errors = $validation->errors();
            return $errors->toJson();

        } else{
                $newTask = Task::create([
                    'name' => Request::get('name'),
                    'priority'=>Request::get('priority'),
                    'start_date'=>Request::get('start_date'),
                    'end_date'=> Request::get('end_date')
                ]);

                $newTask->user()->associate(1)->save();

                return new TaskResource($newTask);
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
        // Get the todos
        $task = Task::where('id',$id)->firstOrFail();
        $progress = taskProgress($task->id);
        $task = $task->toArray();
        $task['complete'] = $progress[0];
        $task['count']= $progress[1];
        
        // Return collection of todos as a resource
        return new TaskResource($task);
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
            'name' => 'required|string',
            'priority'=>'nullable|numeric|max:3|min:1',
            'start_date'=>'nullable|date',
            'end_date'=>'nullable|date'
        ]);

        if($validation->fails()){

            $errors = $validation->errors();
            return $errors->toJson();

        } else{
                $task = Task::findOrFail($id);
                $task->update([
                    'name' => Request::get('name'),
                    'priority'=>Request::get('priority') ?? $task->priority,
                    'start_date'=>Request::get('start_date') ?? $task->start_date,
                    'end_date'=> Request::get('end_date') ?? $task->end_date
                ]);

                // $messages = new MessageBag;
                // $messages->add('success','Task updated successfully.');

                return new TaskResource($task);
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
         // Get the post
        $task = Task::findOrFail($id);
        
        //  Delete the task, return as confirmation
        if ($task->delete()) {
            $messages = new MessageBag;
            $messages->add('success','Task deleted successfully.');

            return $messages->toJson();
        }
    }

    public function search(Request $request)
    {
        $pagination = 5;
        $keyword = Request::get('keyword');
        $tasks = Task::where(function ($query) use($keyword) {
        $query->where('name', 'like', '%' . $keyword . '%')->orderBy('id','desc');
      })->paginate($pagination);

    //     whereHas('products', function ($query) use ($searchString){
    //     $query->where('name', 'like', '%'.$searchString.'%');
    // })

    return $this->getTaskTodoCollection($tasks);

    }

    private function getTaskTodoCollection($tasks)
    {
        $current_task = $tasks->first();
        
        if($current_task){
            $todos = Todo::where('task_id',$current_task->id)->get();
        }
        else{
            $todos = [];
        }
        
        $transformedTasks = $tasks->getCollection()->map(function($item) {
                $progress = taskProgress($item->id);
                $item = $item->toArray();
                $item['complete'] = $progress[0];
                $item['count']= $progress[1];
                return $item;
        });
        
        $tasks->setCollection($transformedTasks);

        // return $tasks->toJson();

        // $merged     = $tasks->merge($todos);
        
        // Return collection of tasks as a resource
        return (TaskResource::collection($tasks))->additional(['meta' => [
                    'todos' => $todos,
                ]]); 
    }

     private function getPriority($priorityName)
    {
        $priorityArr = [
         'low' => 1,
         'medium' => 2,
         'high' => 3
        ];

        return $priorityArr[$priorityName];
    }

    private function getStatus($statusName)
    {
        $statusArr = [
         'not_started' => 0,
         'in_progress' => 1,
         'complete' => 2,
         'canceled' => 3
        ];

        return $statusArr[$statusName];
    }
}
