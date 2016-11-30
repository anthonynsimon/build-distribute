<?php

namespace App\Http\Controllers;

use Auth;
use Gate;
use App\User;
use App\Project;
use App\ProjectPermission;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;

class ProjectPermissionController extends Controller
{
    public static function revokeAccess(Request $request)
    {
        if (Gate::denies('adminOnly')) {
            abort(403);
        }
        
        try {
            $project = Project::find($request->only('projectId'))->first();
            $user = User::find($request->only('userId'))->first();
            
            if (!$project || !$user) {
                abort(400);
            }
            
            $result = ProjectPermission::where('user_id', '=', $user->id)->where('project_id', '=', $project->id)->delete();
        } catch (\Exception $e) {
            abort(500);
        }
        
        return redirect()->back();
    }
    
    public static function grantAccess(Request $request)
    {
        if (Gate::denies('adminOnly')) {
            abort(403);
        }
        try {
            $project = Project::find($request->only('projectId'))->first();
            $user = User::find($request->only('userId'))->first();
            
            if (!$project || !$user) {
                abort(400);
            }
            
            $result = ProjectPermission::create([
                'user_id' => $user->id,
                'project_id' => $project->id
            ]);
        } catch (\Exception $e) {
            abort(500);
        }
        
        return redirect()->back();
    }
}
