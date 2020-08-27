<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
	protected $fillable = ['name','priority','status','start_date','end_date'];
    public function todos()
    {
    	return $this->hasMany('App\Todo');
    }

    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}
