<?php

namespace App\Http\Controllers;
use App\Http\Resources\Task as TaskResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Intervention\Image\Facades\Image;
use App\User;
use App\Profile;
use Request;

class ProfileController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }

    public function showProfile()
    {
    	$profile = Auth::user()->profile;

    	return new TaskResource($profile);
    }

    public function updateProfile(Request $request)
    {
    	// $data = Request::all();

    	// $data = $request->validate([
	    //     'image' => 'required|image'
    	// ]);

    	$data = $request->validate([
        'image' => 'required|file|image'
    	]);

    	// return $data['image'];

        // if($validation->fails()){

        //     $errors = $validation->errors();
        //     return $errors->toJson();

        // } else{
                $user = Auth::user();
                if ($data['image']!=null) {

              //   	$imagePath = $data['image']->store('profile','public');

		            // $image = Image::make(public_path("/storage/{$imagePath}"))->fit(500,500);

		            // $image->save();

              //   	$user->profile()->update([
              //       	'image' => $imagePath
              //   	]);

              //   	$profile = $user->profile;

                	return response()->json([
			            'success' => true
			        ]);
                }

                return response()->json([
		            'success' => false
		        ]);
        // }

        // 		return response()->json([
		      //       'success' => false
		      //   ]);
    	
    }

     public function showInfo()
    {
    	$info = Auth::user();

    	return new TaskResource($info);
    }

    public function updateInfo(Request $request)
    {
    	$user = Auth::user();

    	$data = Request::all();
        $data = $data['body'];

        $validation = Validator::make($data,[ 
        'name' => 'required|string',
        'email' => 'required|string|email|unique:users,email,'.$user->id
        ]);

        if($validation->fails()){

            $errors = $validation->errors();

            return $errors->toJson();

        } 

        
        $user->update([
            'name' => $data['name'],
            'email' => $data['email']
        ]);
        $user->save();

		return new TaskResource($user);
    	
    }
}
