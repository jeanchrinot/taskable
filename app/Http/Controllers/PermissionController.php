<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
      public function Permission()
    {   
    	$create_permission = Permission::where('slug','create-tasks')->first();
    	$edit_permission = Permission::where('slug','edit-tasks')->first();
    	$delete_permission = Permission::where('slug','delete-tasks')->first();
    	$view_permission = Permission::where('slug','view-tasks')->first();
		$admin_permission = Permission::where('slug', 'manage-users')->first();

		//RoleTableSeeder.php
		$user_role = new Role();
		$user_role->slug = 'user';
		$user_role->name = 'Normal App User';
		$user_role->save();
		$user_role->permissions()->attach($create_permission);
		$user_role->permissions()->attach($edit_permission);
		$user_role->permissions()->attach($delete_permission);
		$user_role->permissions()->attach($view_permission);

		$admin_role = new Role();
		$admin_role->slug = 'admin';
		$admin_role->name = 'App Administrator';
		$admin_role->save();
		$admin_role->permissions()->attach($admin_permission);
		$admin_role->permissions()->attach($view_permission);


		$user_role = Role::where('slug','user')->first();
		$admin_role = Role::where('slug', 'admin')->first();

		$createTasks = new Permission();
		$createTasks->slug = 'create-tasks';
		$createTasks->name = 'Create Tasks';
		$createTasks->save();
		$createTasks->roles()->attach($user_role);

		$editTasks = new Permission();
		$editTasks->slug = 'edit-tasks';
		$editTasks->name = 'Edit Tasks';
		$editTasks->save();
		$editTasks->roles()->attach($user_role);

		$deleteTasks = new Permission();
		$deleteTasks->slug = 'delete-tasks';
		$deleteTasks->name = 'Delete Tasks';
		$deleteTasks->save();
		$deleteTasks->roles()->attach($user_role);

		$viewTasks = new Permission();
		$viewTasks->slug = 'view-tasks';
		$viewTasks->name = 'View Tasks';
		$viewTasks->save();
		$viewTasks->roles()->attach($user_role);
		$viewTasks->roles()->attach($admin_role);

		$manageUsers = new Permission();
		$manageUsers->slug = 'manage-users';
		$manageUsers->name = 'Manage Users';
		$manageUsers->save();
		$manageUsers->roles()->attach($admin_role);

		$user_role = Role::where('slug','user')->first();
		$admin_role = Role::where('slug', 'admin')->first();
		$create_perm = Permission::where('slug','create-tasks')->first();
		$edit_perm = Permission::where('slug','edit-tasks')->first();
		$delete_perm = Permission::where('slug','delete-tasks')->first();
		$view_perm = Permission::where('slug','view-tasks')->first();
		$admin_perm = Permission::where('slug','manage-users')->first();

		$user = new User();
		$user->name = 'User';
		$user->email = 'user@user.com';
		$user->password = bcrypt('password');
		$user->save();
		$user->roles()->attach($user_role);
		$user->permissions()->attach($create_perm);
		$user->permissions()->attach($edit_perm);
		$user->permissions()->attach($delete_perm);
		$user->permissions()->attach($view_perm);



		$admin = new User();
		$admin->name = 'Admin';
		$admin->email = 'admin@admin.com';
		$admin->password = bcrypt('password');
		$admin->save();
		$admin->roles()->attach($admin_role);
		$admin->permissions()->attach($admin_perm);
		$admin->permissions()->attach($view_perm);

		dd('Complete');
		
		return redirect()->view('login');
    }
}
