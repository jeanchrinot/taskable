<?php

use Illuminate\Database\Seeder;
use App\Task;
use App\Todo;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i < 20; $i++) { 
        	$task = Task::create([
        		'name'=>'My shopping list '.$i,
        		'priority'=>1,
        		'status'=>0,
        		'start_date'=>now(),
        		'end_date'=> date_create("2020-12-31 00:00:00")
        	]);

        	$task->user()->associate(1)->save();

        	for ($j=1; $j <5 ; $j++) { 
        		Todo::create([
        			'name'=>'Shopping list item '.$i.$j
        		])->task()->associate($task->id)->save();
        	}
        }
    }
}
