@extends('layouts.master')
@section('page_title', 'Planner Approvals - Scheme of Studies')
@section('content')

    @if(session('flash_success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            {{ session('flash_success') }}
        </div>
    @endif
    @if(session('flash_danger'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            {{ session('flash_danger') }}
        </div>
    @endif

    {{-- STAT CARDS --}}
    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-warning-400 has-bg-image">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0">{{ $pending_count }}</h3>
                        <span class="text-uppercase font-size-xs font-weight-bold">Pending Review</span>
                    </div>
                    <div class="ml-3 align-self-center"><i class="icon-notification2 icon-3x opacity-75"></i></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-success-400 has-bg-image">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0">{{ $planners->where('status', 'approved')->count() }}</h3>
                        <span class="text-uppercase font-size-xs font-weight-bold">Approved</span>
                    </div>
                    <div class="ml-3 align-self-center"><i class="icon-checkmark3 icon-3x opacity-75"></i></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-danger-400 has-bg-image">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0">{{ $planners->where('status', 'rejected')->count() }}</h3>
                        <span class="text-uppercase font-size-xs font-weight-bold">Needs Revision</span>
                    </div>
                    <div class="ml-3 align-self-center"><i class="icon-cross2 icon-3x opacity-75"></i></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-blue-400 has-bg-image">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0">{{ $planners->count() }}</h3>
                        <span class="text-uppercase font-size-xs font-weight-bold">Total Planners</span>
                    </div>
                    <div class="ml-3 align-self-center"><i class="icon-book2 icon-3x opacity-75"></i></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pending Alert Header --}}
    @if($pending_count > 0)
        <div class="alert alert-warning border-0 shadow-sm mb-4" style="border-left: 5px solid #ffc107 !important; background: #fff8e1;">
            <h6 class="alert-heading font-weight-bold mb-1 text-dark">
                <i class="icon-notification2 mr-2 text-warning"></i>Action Required
            </h6>
            <span class="text-dark">You have <strong>{{ $pending_count }}</strong> planner(s) waiting for principal review and approval.</span>
        </div>
    @endif

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title font-weight-bold"><i class="icon-shield-notice mr-2 text-primary"></i>Planner Approvals & Review List</h6>
            <div class="header-elements">
                <a href="{{ route('planners.consolidated') }}" class="btn btn-sm btn-outline-primary mr-2">
                    <i class="icon-grid5 mr-1"></i> Consolidated Master View
                </a>
                {!! Qs::getPanelOptions() !!}
            </div>
        </div>

        <div class="card-body">
            <table class="table datatable-button-html5-columns">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Teacher</th>
                    <th>Class</th>
                    <th>Subject</th>
                    <th>Type</th>
                    <th>Session</th>
                    <th>Status</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($planners as $key => $p)
                    <tr class="{{ $p->isPending() ? 'table-warning' : '' }}">
                        <td>{{ $key + 1 }}</td>
                        <td><strong class="text-dark">{{ $p->teacher->name ?? 'N/A' }}</strong></td>
                        <td>{{ $p->my_class->name ?? 'N/A' }}</td>
                        <td>{{ $p->subject->name ?? 'N/A' }}</td>
                        <td>{{ $p->planner_type_name }}</td>
                        <td>{{ $p->session }}</td>
                        <td>{!! $p->status_badge !!}</td>
                        <td>{{ $p->updated_at->format('d M Y, h:i A') }}</td>
                        <td>
                            {{-- View --}}
                            <a href="{{ route('planners.show', $p->id) }}" class="btn btn-sm btn-info mr-1" title="View">
                                <i class="icon-eye"></i>
                            </a>

                            {{-- Print --}}
                            <a href="{{ route('planners.print', $p->id) }}" target="_blank" class="btn btn-sm btn-light mr-1" title="Print Document">
                                <i class="icon-printer"></i>
                            </a>

                            {{-- Review --}}
                            @if($p->isPending())
                                <a href="{{ route('planners.review', $p->id) }}" class="btn btn-sm btn-success mr-1" title="Review & Action">
                                    <i class="icon-checkmark3 mr-1"></i> Review
                                </a>
                            @else
                                <a href="{{ route('planners.review', $p->id) }}" class="btn btn-sm btn-light mr-1" title="Inspect Review">
                                    <i class="icon-pencil7 mr-1"></i> Inspect
                                </a>
                            @endif

                            {{-- Delete --}}
                            <form method="POST" action="{{ route('planners.destroy', $p->id) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this planner?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="icon-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
