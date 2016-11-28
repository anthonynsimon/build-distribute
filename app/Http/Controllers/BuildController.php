<?php

namespace App\Http\Controllers;

use Gate;
use DB;
use App\Project;
use App\Tag;
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

        $buildTags = $build->tags;
        $tagsId = [];
        foreach ($buildTags as $tag) {
            array_push($tagsId, $tag->id);
        }
        $availableTags = DB::table('tags')->whereNotIn('id', $tagsId)->get();
    
        return view('common.buildDetail', compact('build', 'buildTags', 'availableTags', 'projectId'));
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
}
