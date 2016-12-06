<?php

namespace App\Http\Controllers;

use Gate;
use App\Project;
use App\Build;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;

class BuildController extends Controller
{

    
    public function show($buildId)
    {
        $build = Build::find($buildId);
        
        if (!$build) {
            abort(404);
        }
        
        if (Gate::denies('viewProject', $build->project->id)) {
            abort(403);
        }
        
        return redirect()->intended('/projects/'.$build->project->name.'/builds/'.$buildId);
    }
    
    public function nestedShow($projectId, $buildId)
    {
        $build = Build::find($buildId);
                
        if (Gate::denies('viewProject', $build->project->id)) {
            abort(403);
        }

        // View expects an array of builds to display
        $builds = [$build];
    
        return view('common.buildsDetail', compact('builds', 'projectId'));
    }

    public function patchBuildNote($projectId, $buildId, Request $request)
    {
        $build = Build::find($buildId);
        
        if (!$build) {
            abort(404);
        }
        
        if (Gate::denies('adminOnly', $build->project->id)) {
            abort(403);
        }

        $note = $request->input('note');

        $build->note = $note;
        $build->save();
        
        return redirect()->back();
    }

    public function tag($projectId, $buildId, Request $request)
    {
        $build = Build::find($buildId);
        
        if (!$build) {
            abort(404);
        }
        
        if (Gate::denies('adminOnly', $build->project->id)) {
            abort(403);
        }

        $tagName = $request->input('tagName');

        $build->tag($tagName);
        
        return redirect()->back();
    }

    public function untag($projectId, $buildId, $tagName)
    {
        $build = Build::find($buildId);
        
        if (!$build) {
            abort(404);
        }
        
        if (Gate::denies('adminOnly', $build->project->id)) {
            abort(403);
        }

        $build->untag($tagName);
        
        return redirect()->back();
    }
}
