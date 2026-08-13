<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Models\Planner;
use App\Repositories\UserRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected $user;

    public function __construct(UserRepo $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        return redirect()->route('dashboard');
    }

    public function privacy_policy()
    {
        $data['app_name'] = config('app.name');
        $data['app_url'] = config('app.url');
        $data['contact_phone'] = Qs::getSetting('phone');
        return view('pages.other.privacy_policy', $data);
    }

    public function terms_of_use()
    {
        $data['app_name'] = config('app.name');
        $data['app_url'] = config('app.url');
        $data['contact_phone'] = Qs::getSetting('phone');
        return view('pages.other.terms_of_use', $data);
    }

    /**
     * Switch Dashboard View Mode (Super Admin / Admin only)
     */
    public function switch_dashboard_view($role)
    {
        if (!Qs::userIsSuperAdmin() && !Qs::userIsAdmin()) {
            return back()->with('pop_error', __('msg.denied'));
        }

        $allowed = ['super_admin', 'admin', 'teacher', 'parent', 'student'];
        if (in_array($role, $allowed)) {
            session(['dashboard_view_role' => $role]);
        }

        return back()->with('flash_success', 'Dashboard view switched to ' . ucwords(str_replace('_', ' ', $role)));
    }

    public function dashboard()
    {
        $d = [];
        $view_role = session('dashboard_view_role', Auth::user()->user_type);

        // Regular users only see their own dashboard view
        if (!Qs::userIsSuperAdmin() && !Qs::userIsAdmin()) {
            $view_role = Auth::user()->user_type;
        }

        $d['active_view_role'] = $view_role;

        if (Qs::userIsTeamSAT()) {
            $d['users'] = $this->user->getAll();
        }

        // Planner Stats for Dashboard Widgets
        if (in_array($view_role, ['super_admin', 'admin'])) {
            $d['pending_planners_count'] = Planner::pending()->count();
            $d['approved_planners_count'] = Planner::approved()->count();
            $d['rejected_planners_count'] = Planner::rejected()->count();
            $d['recent_pending_planners'] = Planner::pending()->with(['teacher', 'my_class', 'subject'])->latest()->take(5)->get();
        } elseif ($view_role === 'teacher') {
            $teacher_id = Auth::user()->id;
            $d['my_planners_count'] = Planner::where('teacher_id', $teacher_id)->count();
            $d['my_drafts_count'] = Planner::where('teacher_id', $teacher_id)->draft()->count();
            $d['my_pending_count'] = Planner::where('teacher_id', $teacher_id)->pending()->count();
            $d['my_approved_count'] = Planner::where('teacher_id', $teacher_id)->approved()->count();
            $d['my_rejected_count'] = Planner::where('teacher_id', $teacher_id)->rejected()->count();
            $d['recent_my_planners'] = Planner::where('teacher_id', $teacher_id)->with(['my_class', 'subject'])->latest()->take(5)->get();
        }

        return view('pages.support_team.dashboard', $d);
    }
}
