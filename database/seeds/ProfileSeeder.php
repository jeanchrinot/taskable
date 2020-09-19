<?php

use Illuminate\Database\Seeder;
use App\Profile;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$imagePath = '/assets/images/user-nophoto.jpg';
        Profile::create([
        	'image'=>$imagePath
        ])->user()->associate(1)->save();

        Profile::create([
        	'image'=>$imagePath
        ])->user()->associate(2)->save();

        Profile::create([
        	'image'=>$imagePath
        ])->user()->associate(3)->save();
    }
}
