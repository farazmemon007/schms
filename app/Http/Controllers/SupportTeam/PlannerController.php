<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\MyClass;
use App\Models\Planner;
use App\Models\PlannerItem;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSAT');
        $this->middleware('teamSA', ['only' => ['adminIndex', 'review', 'approve', 'reject']]);
    }

    /**
     * Teacher: List all planners
     */
    public function index()
    {
        $d = [];
        if (Qs::userIsTeamSA()) {
            // Admin sees all planners
            $d['planners'] = Planner::with(['teacher', 'my_class', 'subject'])
                ->orderBy('updated_at', 'desc')
                ->get();
        } else {
            // Teacher sees only their own
            $d['planners'] = Planner::where('teacher_id', Auth::user()->id)
                ->with(['my_class', 'subject'])
                ->orderBy('updated_at', 'desc')
                ->get();
        }
        return view('pages.support_team.planners.index', $d);
    }

    /**
     * Teacher: Select class then subject to create planner
     */
    public function selectClass($class_id)
    {
        $d['my_class'] = MyClass::findOrFail($class_id);

        if (Qs::userIsTeamSA()) {
            $d['subjects'] = Subject::where('my_class_id', $class_id)->orderBy('name')->get();
        } else {
            // Teacher only sees subjects assigned to them for this class
            $d['subjects'] = Subject::where('my_class_id', $class_id)
                ->where('teacher_id', Auth::user()->id)
                ->orderBy('name')
                ->get();
        }

        return view('pages.support_team.planners.select_class', $d);
    }

    /**
     * Teacher: Create planner form
     */
    public function create($class_id, $subject_id)
    {
        $d['my_class'] = MyClass::findOrFail($class_id);
        $d['subject'] = Subject::findOrFail($subject_id);
        $d['current_session'] = Qs::getCurrentSession();

        // Check if planner already exists for this combination
        $existing = Planner::where([
            'teacher_id' => Auth::user()->id,
            'my_class_id' => $class_id,
            'subject_id' => $subject_id,
            'session' => $d['current_session'],
        ])->first();

        if ($existing) {
            return redirect()->route('planners.edit', $existing->id)
                ->with('flash_info', 'A planner already exists for this class/subject. You can edit it here.');
        }

        $d['months'] = $this->getPlannerMonths();
        return view('pages.support_team.planners.create', $d);
    }

    /**
     * Teacher: Store new planner
     */
    public function store(Request $req)
    {
        $req->validate([
            'my_class_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'planner_type' => 'required|in:mid_term,final_term',
            'months' => 'required|array',
            'months.*' => 'required|string',
            'chapters' => 'required|array',
            'chapters.*' => 'required|string',
        ]);

        $action = $req->input('action', 'save'); // 'save' or 'submit'

        $planner = Planner::create([
            'teacher_id' => Auth::user()->id,
            'my_class_id' => $req->my_class_id,
            'subject_id' => $req->subject_id,
            'planner_type' => $req->planner_type,
            'session' => Qs::getCurrentSession(),
            'status' => $action === 'submit' ? 'pending' : 'draft',
        ]);

        // Save planner items
        $this->savePlannerItems($planner, $req);

        $msg = $action === 'submit'
            ? 'Planner submitted for approval successfully!'
            : 'Planner saved as draft successfully!';

        return redirect()->route('planners.index')->with('flash_success', $msg);
    }

    /**
     * Teacher/Admin: View planner (read-only)
     */
    public function show($id)
    {
        $d['planner'] = Planner::with(['items', 'teacher', 'my_class', 'subject', 'reviewer'])->findOrFail($id);

        // Teachers can only view their own planners
        if (!Qs::userIsTeamSA() && $d['planner']->teacher_id !== Auth::user()->id) {
            return redirect()->route('planners.index')->with('flash_danger', __('msg.denied'));
        }

        return view('pages.support_team.planners.show', $d);
    }

    /**
     * Teacher: Edit planner form
     */
    public function edit($id)
    {
        $d['planner'] = Planner::with(['items', 'my_class', 'subject'])->findOrFail($id);

        // Only owner can edit
        if (!Qs::userIsTeamSA() && $d['planner']->teacher_id !== Auth::user()->id) {
            return redirect()->route('planners.index')->with('flash_danger', __('msg.denied'));
        }

        // Can only edit draft or rejected planners
        if (!$d['planner']->canEdit()) {
            return redirect()->route('planners.show', $id)
                ->with('flash_danger', 'This planner cannot be edited in its current status.');
        }

        $d['months'] = $this->getPlannerMonths();
        return view('pages.support_team.planners.edit', $d);
    }

    /**
     * Teacher: Update planner
     */
    public function update(Request $req, $id)
    {
        $planner = Planner::findOrFail($id);

        // Only owner can update
        if (!Qs::userIsTeamSA() && $planner->teacher_id !== Auth::user()->id) {
            return redirect()->route('planners.index')->with('flash_danger', __('msg.denied'));
        }

        if (!$planner->canEdit()) {
            return redirect()->route('planners.show', $id)
                ->with('flash_danger', 'This planner cannot be edited in its current status.');
        }

        $req->validate([
            'planner_type' => 'required|in:mid_term,final_term',
            'months' => 'required|array',
            'months.*' => 'required|string',
            'chapters' => 'required|array',
            'chapters.*' => 'required|string',
        ]);

        $action = $req->input('action', 'save');

        $planner->update([
            'planner_type' => $req->planner_type,
            'status' => $action === 'submit' ? 'pending' : 'draft',
            'admin_remarks' => $action === 'submit' ? null : $planner->admin_remarks,
        ]);

        // Clear old items and re-create
        $planner->items()->delete();
        $this->savePlannerItems($planner, $req);

        // Reset highlights if resubmitting
        if ($action === 'submit') {
            PlannerItem::where('planner_id', $planner->id)
                ->update(['is_highlighted' => false, 'highlight_comment' => null]);
        }

        $msg = $action === 'submit'
            ? 'Planner resubmitted for approval successfully!'
            : 'Planner saved as draft successfully!';

        return redirect()->route('planners.index')->with('flash_success', $msg);
    }

    /**
     * Teacher: Submit draft planner for approval
     */
    public function submit($id)
    {
        $planner = Planner::findOrFail($id);

        if (!Qs::userIsTeamSA() && $planner->teacher_id !== Auth::user()->id) {
            return redirect()->route('planners.index')->with('flash_danger', __('msg.denied'));
        }

        if (!$planner->canEdit()) {
            return redirect()->route('planners.index')
                ->with('flash_danger', 'This planner cannot be submitted in its current status.');
        }

        // Check if planner has items
        if ($planner->items()->count() === 0) {
            return redirect()->route('planners.edit', $id)
                ->with('flash_danger', 'Cannot submit an empty planner. Please add data first.');
        }

        $planner->update([
            'status' => 'pending',
            'admin_remarks' => null,
        ]);

        // Reset highlights
        PlannerItem::where('planner_id', $planner->id)
            ->update(['is_highlighted' => false, 'highlight_comment' => null]);

        return redirect()->route('planners.index')
            ->with('flash_success', 'Planner submitted to admin for approval!');
    }

    /**
     * Admin: List all planners for review
     */
    public function adminIndex()
    {
        $d['planners'] = Planner::with(['teacher', 'my_class', 'subject'])
            ->orderByRaw("FIELD(status, 'pending', 'rejected', 'draft', 'approved')")
            ->orderBy('updated_at', 'desc')
            ->get();

        $d['pending_count'] = Planner::pending()->count();
        return view('pages.support_team.planners.admin_index', $d);
    }

    /**
     * Admin: Review a planner
     */
    public function review($id)
    {
        $d['planner'] = Planner::with(['items', 'teacher', 'my_class', 'subject'])->findOrFail($id);
        return view('pages.support_team.planners.review', $d);
    }

    /**
     * Admin: Approve a planner
     */
    public function approve($id)
    {
        $planner = Planner::findOrFail($id);

        $planner->update([
            'status' => 'approved',
            'reviewed_by' => Auth::user()->id,
            'reviewed_at' => now(),
            'admin_remarks' => null,
        ]);

        // Clear any highlights
        PlannerItem::where('planner_id', $planner->id)
            ->update(['is_highlighted' => false, 'highlight_comment' => null]);

        return redirect()->route('planners.admin')
            ->with('flash_success', 'Planner approved successfully!');
    }

    /**
     * Admin: Reject a planner with remarks and highlighted issues
     */
    public function reject(Request $req, $id)
    {
        $req->validate([
            'admin_remarks' => 'required|string|min:5',
        ]);

        $planner = Planner::findOrFail($id);

        $planner->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::user()->id,
            'reviewed_at' => now(),
            'admin_remarks' => $req->admin_remarks,
        ]);

        // Save highlighted items
        if ($req->has('highlighted_items')) {
            $highlights = $req->highlighted_items;
            $comments = $req->highlight_comments ?? [];

            foreach ($planner->items as $item) {
                $isHighlighted = in_array($item->id, $highlights);
                $item->update([
                    'is_highlighted' => $isHighlighted,
                    'highlight_comment' => $isHighlighted ? ($comments[$item->id] ?? null) : null,
                ]);
            }
        }

        return redirect()->route('planners.admin')
            ->with('flash_success', 'Planner sent back to teacher with remarks.');
    }

    /**
     * Helper: Save planner items from request
     */
    private function savePlannerItems(Planner $planner, Request $req)
    {
        $months = $req->months;
        $chapters = $req->chapters;
        $topics = $req->topics ?? [];
        $teaching_methods = $req->teaching_methods ?? [];
        $assessments = $req->assessments ?? [];
        $remarks = $req->item_remarks ?? [];

        foreach ($months as $key => $month) {
            if (empty($month) && empty($chapters[$key] ?? '')) {
                continue; // Skip completely empty rows
            }

            PlannerItem::create([
                'planner_id' => $planner->id,
                'month' => $month ?? '',
                'chapters' => $chapters[$key] ?? '',
                'topics' => $topics[$key] ?? null,
                'teaching_methods' => $teaching_methods[$key] ?? null,
                'assessment' => $assessments[$key] ?? null,
                'remarks' => $remarks[$key] ?? null,
                'sort_order' => $key,
            ]);
        }
    }

    /**
     * Consolidated Scheme of Studies Matrix View for Class & Term
     */
    public function consolidated(Request $req)
    {
        $d['my_classes'] = Qs::getAssignedClasses();
        $d['selected_class_id'] = $req->class_id ?? ($d['my_classes']->first()->id ?? null);
        $d['selected_term'] = $req->planner_type ?? 'mid_term';
        $d['selected_session'] = $req->session ?? Qs::getCurrentSession();

        if ($d['selected_class_id']) {
            $d['my_class'] = MyClass::find($d['selected_class_id']);
            $d['planners'] = Planner::where([
                'my_class_id' => $d['selected_class_id'],
                'planner_type' => $d['selected_term'],
                'session' => $d['selected_session'],
                'status' => 'approved',
            ])->with(['items', 'subject', 'teacher'])->get();
        } else {
            $d['planners'] = collect();
        }

        $d['months'] = $this->getPlannerMonths();
        return view('pages.support_team.planners.consolidated', $d);
    }

    /**
     * Official Printable View of Planner
     */
    public function print($id)
    {
        $d['planner'] = Planner::with(['items', 'teacher', 'my_class', 'subject', 'reviewer'])->findOrFail($id);

        if (!Qs::userIsTeamSA() && $d['planner']->teacher_id !== Auth::user()->id) {
            return redirect()->route('planners.index')->with('flash_danger', __('msg.denied'));
        }

        return view('pages.support_team.planners.print', $d);
    }

    /**
     * Delete Planner (Draft / Rejected or by Admin)
     */
    public function destroy($id)
    {
        $planner = Planner::findOrFail($id);

        if (!Qs::userIsTeamSA() && $planner->teacher_id !== Auth::user()->id) {
            return redirect()->route('planners.index')->with('flash_danger', __('msg.denied'));
        }

        if (!$planner->canDelete() && !Qs::userIsTeamSA()) {
            return redirect()->route('planners.index')->with('flash_danger', 'Cannot delete an approved or submitted planner.');
        }

        $planner->items()->delete();
        $planner->delete();

        return redirect()->route('planners.index')->with('flash_success', 'Planner deleted successfully.');
    }

    /**
     * Helper: Get list of months for planner
     */
    private function getPlannerMonths()
    {
        return [
            'April', 'May', 'June', 'July', 'August',
            'September', 'October', 'November', 'December',
            'January', 'February', 'March'
        ];
    }
}
