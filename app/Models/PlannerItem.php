<?php

namespace App\Models;

use Eloquent;

class PlannerItem extends Eloquent
{
    protected $fillable = [
        'planner_id', 'month', 'chapters', 'topics',
        'teaching_methods', 'assessment', 'remarks',
        'is_highlighted', 'highlight_comment', 'sort_order'
    ];

    protected $casts = [
        'is_highlighted' => 'boolean',
    ];

    public function getMonthNameAttribute()
    {
        if (is_numeric($this->month) && $this->month >= 1 && $this->month <= 12) {
            return date('F', mktime(0, 0, 0, $this->month, 10));
        }
        return ucfirst(trim($this->month));
    }

    public function planner()
    {
        return $this->belongsTo(Planner::class);
    }
}
