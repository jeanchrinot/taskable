<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    public function task()
    {
    	return $this->belongsTo('App\Task');
    }
}
