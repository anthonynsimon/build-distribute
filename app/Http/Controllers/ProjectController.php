<?php

namespace App\Http\Controllers;

use Gate;
use Validator;
use App\User;
use App\Project;
use App\Build;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;

class ProjectController extends Controller
{	
	public function index()
    {	
		return view('common.buildsList');
    }

    public function show($id)
    {
		$project = Project::findByIdOrName($id);
		
		if (!$project)
		{
			abort(404);
		}
			
		if (Gate::denies('viewProject', $project->id)) {
			abort(403);
		}
				
		$builds = $project->builds()->orderBy('created_at', 'desc')->get();
						
		return view('common.buildsList', compact('project', 'builds'));
    }
	
	public function create()
    {
		if (Gate::denies('adminOnly')) {
			abort(403);
		}
		
		return view('common.createProject');
    }
	
	public function edit(Request $request)
    {
		$projectId = urldecode(explode('/', $request->path())[1]);
		$project = Project::findByIdOrName($projectId);
		
		if (!$project) {
			abort(404);
		}
		
		return view('common.editProject', compact('project'));
    }
	
    public function store(Request $request)
    {
		if (Gate::denies('adminOnly')) {
			abort(403);
		}
			
		$input = $request->all();
			
		$validator = Validator::make($input, [
			'name' => 'required|unique:projects',
			'ident' => 'required|unique:projects'
		]);
		
		if ($validator->fails()) {
			return redirect()->back()
				->withInput($request->all())
				->withErrors($validator->errors());
		}
		
		$project = Project::create($request->all());
	
		return redirect()->intended('/');
    }
	
	public function update(Request $request, $projectId)
    {
		$project = Project::findByIdOrName($projectId);
		
		if (!$project) {
			abort(404);
		}
		
		if (Gate::denies('adminOnly', $project->id)) {
			abort(403);
		}
		
		$input = $request->all();
			
		$validator = Validator::make($input, [
			'name' => 'required|unique:projects',
		]);
		
		if ($validator->fails()) {
			return redirect()->back()
				->withInput($request->all())
				->withErrors($validator->errors());
		}
		
		
		
		$project->update($request->only('name'));
	
		return redirect()->intended('/');
    }
}
