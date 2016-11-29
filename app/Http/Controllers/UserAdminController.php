<?php

namespace App\Http\Controllers;

use Auth;
use Gate;
use App\Role;
use App\User;
use App\Project;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;

class UserAdminController extends Controller
{
	public function index() {
		return redirect('/admin/users');
	}
	
    public function indexUsers()
    {
		if (Gate::denies('adminOnly')) {
			abort(403);
		}
		
		$users = User::all();
		
        return view('admin.usersList', compact('users'));
    }
	
	public function showUser($userId)
    {
		if (Gate::denies('adminOnly')) {
			abort(403);
		}
		
		$projects = Project::all();
		$user = User::find($userId);
		$roles = Role::all();
				
        return view('admin.userDetail', compact('projects', 'user', 'roles'));
    }
	
	public function updateUserRole(Request $request, $userId)
	{
		if (Gate::denies('adminOnly')) {
			abort(403);
		}
		
		$roleId = $request->only('roleId');
		
		$user = User::where('id', '=', $userId)->first();
		$role = Role::where('id', '=', $roleId)->first();
		
		if ($user && $role) {
			$user->role_id = $role->id;
			$user->save();
		}
		
		return redirect()->back();
	}
	
	public function destroyUser(Request $request, $userId)
	{
		if (Gate::denies('adminOnly')) {
			abort(403);
		}
		
		$user = User::find($userId);
		
		// Protect against manipulated post url
		if (!$user || $user->email !== $request->input('email')) {
			abort(404);
		}
		
		if ($user->hasRole('superAdmin')) {
			abort(403);
		}
		
		$user->deleteAndCascade();
		
		return redirect('/admin/users');
	}
}
