<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
	protected $fillable = ['name','complete'];
    public function task()
    {
    	return $this->belongsTo('App\Task');
    }
}
