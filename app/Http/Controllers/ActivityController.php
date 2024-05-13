<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    // User Activity Log
    public function index(Request $request){
        $activityLog = Activity::latest()->paginate(10);
        $ipAddress = $request->ip();

        return view('pages.users-activity.index',[
        'logs' => $activityLog,
        'ip' => $ipAddress
        ]);
    }
}
