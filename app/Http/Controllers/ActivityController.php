<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivityController extends Controller
{
    // User Activity Log
    public function index(Request $request){
        return view('pages.users-activity.index');
    }
}
