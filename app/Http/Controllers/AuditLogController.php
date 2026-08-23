<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\LoginLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $activities = ActivityLog::with('user')->latest()->paginate(10, ['*'], 'activity_page');
        $logins = LoginLog::with('user')->latest('login_at')->paginate(10, ['*'], 'login_page');

        return view('admin.audit.index', compact('activities', 'logins'));
    }
}
