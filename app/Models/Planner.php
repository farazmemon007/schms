<?php

namespace App\Models;

use App\User;
use Eloquent;

class Planner extends Eloquent
{
    protected $fillable = [
        'teacher_id', 'my_class_id', 'subject_id', 'planner_type',
        'session', 'status', 'admin_remarks', 'reviewed_by', 'reviewed_at'
    ];

    protected $dates = ['reviewed_at'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function my_class()
    {
        return $this->belongsTo(MyClass::class, 'my_class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function items()
    {
        return $this->hasMany(PlannerItem::class)->orderBy('sort_order');
    }

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Helpers
    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function canEdit()
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    public function canDelete()
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'draft':
                return '<span class="badge badge-secondary">Draft</span>';
            case 'pending':
                return '<span class="badge badge-warning">Pending Approval</span>';
            case 'approved':
                return '<span class="badge badge-success">Approved</span>';
            case 'rejected':
                return '<span class="badge badge-danger">Needs Revision</span>';
            default:
                return '<span class="badge badge-default">' . ucfirst($this->status) . '</span>';
        }
    }

    public function getPlannerTypeNameAttribute()
    {
        return $this->planner_type === 'mid_term' ? 'Mid Term' : 'Final Term';
    }
}
