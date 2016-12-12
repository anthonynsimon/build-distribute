<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Project;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;

class HomeController extends Controller
{

    public static function index(Request $request, Guard $auth)
    {
        if (!Auth::guest()) {
            return redirect('/projects');
        }
        return view('builds.buildList');
    }
}
