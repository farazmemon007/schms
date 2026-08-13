@extends('layouts.master')
@section('page_title', 'Dashboard')
@section('content')

    @if(session('flash_success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            {{ session('flash_success') }}
        </div>
    @endif

    {{-- SUPER ADMIN / ADMIN DASHBOARD VIEW SWITCHER --}}
    @if(Qs::userIsSuperAdmin() || Qs::userIsAdmin())
        <div class="card bg-dark text-white mb-4 shadow-sm" style="border-radius: 8px;">
            <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between flex-wrap">
                <div class="d-flex align-items-center my-1">
                    <i class="icon-display4 icon-2x text-warning mr-2"></i>
                    <div>
                        <strong class="font-weight-bold">Dashboard View Mode:</strong>
                        <span class="badge badge-warning ml-2 font-size-sm text-uppercase">
                            {{ str_replace('_', ' ', $active_view_role) }} View
                        </span>
                    </div>
                </div>
                <div class="btn-group my-1">
                    <button type="button" class="btn btn-sm btn-outline-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-switch mr-1"></i> Switch Dashboard View
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <h6 class="dropdown-header">Select Dashboard View</h6>
                        <a href="{{ route('dashboard.switch_view', 'super_admin') }}" class="dropdown-item {{ $active_view_role == 'super_admin' ? 'active' : '' }}">
                            <i class="icon-user-tie mr-2 text-primary"></i> Super Admin View
                        </a>
                        <a href="{{ route('dashboard.switch_view', 'admin') }}" class="dropdown-item {{ $active_view_role == 'admin' ? 'active' : '' }}">
                            <i class="icon-shield-notice mr-2 text-info"></i> Principal / Admin View
                        </a>
                        <a href="{{ route('dashboard.switch_view', 'teacher') }}" class="dropdown-item {{ $active_view_role == 'teacher' ? 'active' : '' }}">
                            <i class="icon-users2 mr-2 text-success"></i> Teacher View
                        </a>
                        <a href="{{ route('dashboard.switch_view', 'parent') }}" class="dropdown-item {{ $active_view_role == 'parent' ? 'active' : '' }}">
                            <i class="icon-user mr-2 text-indigo"></i> Parent View
                        </a>
                        <a href="{{ route('dashboard.switch_view', 'student') }}" class="dropdown-item {{ $active_view_role == 'student' ? 'active' : '' }}">
                            <i class="icon-reading mr-2 text-warning"></i> Student View
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- PRINCIPAL / ADMIN DASHBOARD VIEW --}}
    @if(in_array($active_view_role, ['super_admin', 'admin']))

        {{-- Pending Planner Action Required Alert --}}
        @if(($pending_planners_count ?? 0) > 0)
            <div class="alert alert-warning border-0 shadow-sm mb-4" style="border-left: 5px solid #ffc107 !important; background: #fff8e1;">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div class="my-1">
                        <h6 class="alert-heading font-weight-bold text-dark mb-1">
                            <i class="icon-notification2 mr-2 text-warning"></i>Scheme of Studies - Action Required
                        </h6>
                        <span class="text-dark">You have <strong>{{ $pending_planners_count }}</strong> lesson planner(s) pending review and approval.</span>
                    </div>
                    <div class="my-1">
                        <a href="{{ route('planners.admin') }}" class="btn btn-warning btn-sm font-weight-bold">
                            <i class="icon-checkmark3 mr-1"></i> Review Planners Now
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            {{-- Total Students --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-blue-400 has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mb-0">{{ isset($users) ? $users->where('user_type', 'student')->count() : 0 }}</h3>
                            <span class="text-uppercase font-size-xs font-weight-bold">Total Students</span>
                        </div>
                        <div class="ml-3 align-self-center">
                            <i class="icon-users4 icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Teachers --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-danger-400 has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mb-0">{{ isset($users) ? $users->where('user_type', 'teacher')->count() : 0 }}</h3>
                            <span class="text-uppercase font-size-xs font-weight-bold">Total Teachers</span>
                        </div>
                        <div class="ml-3 align-self-center">
                            <i class="icon-users2 icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pending Planners --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-warning-400 has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mb-0">{{ $pending_planners_count ?? 0 }}</h3>
                            <span class="text-uppercase font-size-xs font-weight-bold">Pending Planners</span>
                        </div>
                        <div class="ml-3 align-self-center">
                            <i class="icon-paperplane icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Approved Planners --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-success-400 has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mb-0">{{ $approved_planners_count ?? 0 }}</h3>
                            <span class="text-uppercase font-size-xs font-weight-bold">Approved Planners</span>
                        </div>
                        <div class="ml-3 align-self-center">
                            <i class="icon-checkmark3 icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Pending Planners Widget --}}
        @if(isset($recent_pending_planners) && $recent_pending_planners->count() > 0)
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title font-weight-bold"><i class="icon-clipboard3 mr-2 text-primary"></i>Pending Planner Submissions</h6>
                    <a href="{{ route('planners.admin') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="bg-light">
                        <tr>
                            <th>Teacher</th>
                            <th>Class</th>
                            <th>Subject</th>
                            <th>Term</th>
                            <th>Submitted At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($recent_pending_planners as $rp)
                            <tr>
                                <td><strong>{{ $rp->teacher->name ?? 'N/A' }}</strong></td>
                                <td>{{ $rp->my_class->name ?? 'N/A' }}</td>
                                <td>{{ $rp->subject->name ?? 'N/A' }}</td>
                                <td>{{ $rp->planner_type_name }}</td>
                                <td>{{ $rp->updated_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('planners.review', $rp->id) }}" class="btn btn-sm btn-success">
                                        <i class="icon-checkmark3 mr-1"></i> Review
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    {{-- TEACHER DASHBOARD VIEW --}}
    @elseif($active_view_role === 'teacher')

        <div class="row">
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-indigo-400">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mb-0">{{ $my_planners_count ?? 0 }}</h3>
                            <span class="text-uppercase font-size-xs font-weight-bold">My Total Planners</span>
                        </div>
                        <div class="ml-3 align-self-center"><i class="icon-book2 icon-3x opacity-75"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-secondary">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mb-0">{{ $my_drafts_count ?? 0 }}</h3>
                            <span class="text-uppercase font-size-xs font-weight-bold">Draft Planners</span>
                        </div>
                        <div class="ml-3 align-self-center"><i class="icon-pencil5 icon-3x opacity-75"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-warning-400">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mb-0">{{ $my_pending_count ?? 0 }}</h3>
                            <span class="text-uppercase font-size-xs font-weight-bold">Under Review</span>
                        </div>
                        <div class="ml-3 align-self-center"><i class="icon-spinner2 icon-3x opacity-75"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-success-400">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mb-0">{{ $my_approved_count ?? 0 }}</h3>
                            <span class="text-uppercase font-size-xs font-weight-bold">Approved Planners</span>
                        </div>
                        <div class="ml-3 align-self-center"><i class="icon-checkmark3 icon-3x opacity-75"></i></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions for Teacher --}}
        <div class="card mb-4">
            <div class="card-header header-elements-inline">
                <h6 class="card-title font-weight-bold"><i class="icon-rocket mr-2 text-primary"></i>Teacher Quick Actions</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('planners.index') }}" class="btn btn-primary mr-2 mb-2">
                    <i class="icon-list mr-1"></i> My Scheme of Studies List
                </a>
                <a href="{{ route('planners.consolidated') }}" class="btn btn-info mr-2 mb-2">
                    <i class="icon-grid5 mr-1"></i> Consolidated Master Planner
                </a>
            </div>
        </div>

    @endif

    {{-- Events Calendar --}}
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title font-weight-bold">School Events Calendar</h5>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="fullcalendar-basic"></div>
        </div>
    </div>

@endsection
