<?php

namespace App\Http\Controllers;

use Validator;
use Gate;
use App\Build;
use App\Tag;
use App\Http\Requests;
use Illuminate\Http\Request;

class BuildTagsController extends Controller
{
    public function index()
    {
        dd(Tag::all());
        return;
    }

    public function createTag(Request $request)
    {
        if (Gate::denies('adminOnly')) {
            abort(403);
        }

        $input = $request->all();
            
        $validator = Validator::make($input, [
            'name' => 'required|unique:tags',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($validator->errors());
        }
        
        $tag = Tag::create($request->all());
    
        return redirect()->back();
    }

    public function deleteTag($tagId, Request $request)
    {
        if (Gate::denies('adminOnly')) {
            abort(403);
        }

        $tag = Tag::find($tagId);

        if (empty($tag)) {
            abort(404);
        }

        $tag->delete();

        return redirect()->back();
    }
    
    public function assignBuildTag($buildId, Request $request)
    {
        if (Gate::denies('adminOnly')) {
            abort(403);
        }

        $tagId = $request->input('tagId');

        if (empty($tagId)) {
            abort(400);
        }

        $tag = Tag::find($tagId);
        $build = Build::find($buildId);

        if (empty($tag) || empty($build)) {
            abort(404);
        }

        $build->tags()->attach($tag->id);

        return redirect()->back();
    }
    
    public function removeBuildTag($buildId, $tagId, Request $request)
    {
        if (Gate::denies('adminOnly')) {
            abort(403);
        }

        $tag = Tag::find($tagId);
        $build = Build::find($buildId);

        if (empty($tag) || empty($build)) {
            abort(404);
        }

        $build->tags()->dettach($tag->id);

        return redirect()->back();
    }
}
