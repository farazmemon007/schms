@extends('layouts.master')
@section('page_title', 'My Planners - Scheme of Studies')
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
    @if(session('flash_info'))
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            {{ session('flash_info') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title font-weight-bold"><i class="icon-book2 mr-2 text-primary"></i>Scheme of Studies Planners</h6>
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
                    @if(Qs::userIsTeamSA())
                        <th>Teacher</th>
                    @endif
                    <th>Class</th>
                    <th>Subject</th>
                    <th>Planner Type</th>
                    <th>Session</th>
                    <th>Status</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($planners as $key => $p)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        @if(Qs::userIsTeamSA())
                            <td><strong class="text-dark">{{ $p->teacher->name ?? 'N/A' }}</strong></td>
                        @endif
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

                            {{-- Edit (only for draft/rejected) --}}
                            @if($p->canEdit() && (!Qs::userIsTeamSA() || $p->teacher_id == Auth::user()->id))
                                <a href="{{ route('planners.edit', $p->id) }}" class="btn btn-sm btn-warning mr-1" title="Edit">
                                    <i class="icon-pencil"></i>
                                </a>
                            @endif

                            {{-- Submit to Admin (only for draft) --}}
                            @if($p->isDraft() && (!Qs::userIsTeamSA() || $p->teacher_id == Auth::user()->id))
                                <form method="POST" action="{{ route('planners.submit', $p->id) }}" style="display:inline;">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-primary mr-1" title="Submit to Principal"
                                            onclick="return confirm('Are you sure you want to submit this planner for principal approval?')">
                                        <i class="icon-paperplane"></i>
                                    </button>
                                </form>
                            @endif

                            {{-- Admin: Review --}}
                            @if(Qs::userIsTeamSA() && $p->isPending())
                                <a href="{{ route('planners.review', $p->id) }}" class="btn btn-sm btn-success mr-1" title="Review">
                                    <i class="icon-checkmark3"></i> Review
                                </a>
                            @endif

                            {{-- Delete (Draft or SA) --}}
                            @if($p->canDelete() || Qs::userIsTeamSA())
                                <form method="POST" action="{{ route('planners.destroy', $p->id) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this planner?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="icon-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
