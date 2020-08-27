<?php

function taskStatus($id)
{
	$status = [];
	$task = \App\Task::find($id);
	if($task){
		switch ($task->status) {
			case 0:
				$status = ['0','Not started','warning'];
				break;
			case 1:
				$status = ['1','In progress','info'];
				break;
			case 2:
				$status = ['2','Complete','success'];
				break;
			case -1:
				$status = ['-1','Canceled','danger'];
				break;
			default:
				# code...
				break;
		}
	}

	return $status;
}

function taskProgress($id)
{
	$progress = array();

	$task = \App\Task::find($id);
	if($task){

		$complete = $task->todos->where('complete',1)->count();
		$count = $task->todos->count();
		$progress = [$complete,$count];
	}

	return $progress;
	
}

function taskPriority($id)
{
	$priority = array();

	$task = \App\Task::find($id);
	if($task){
		switch ($task->priority) {
			case 1:
				$priority = ['1','Low','light'];
				break;
			case 2:
				$priority = ['2','Medium','warning'];
				break;
			case 3:
				$priority = ['3','High','danger'];
				break;
			default:
				# code...
				break;
		}
	}

	return $priority;
}