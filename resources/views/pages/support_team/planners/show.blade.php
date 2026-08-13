@extends('layouts.master')
@section('page_title', 'View Planner - '.$planner->my_class->name.' - '.$planner->subject->name)
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

    {{-- Show Admin Remarks if Rejected --}}
    @if($planner->isRejected() && $planner->admin_remarks)
        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-left: 5px solid #dc3545 !important;">
            <h6 class="alert-heading font-weight-bold"><i class="icon-warning mr-2"></i>Principal / Admin Remarks - Action Required:</h6>
            <p class="mb-1 font-size-lg">{{ $planner->admin_remarks }}</p>
            @if($planner->reviewer)
                <small class="text-muted">Reviewed by: <strong>{{ $planner->reviewer->name }}</strong> on {{ $planner->reviewed_at ? $planner->reviewed_at->format('d M Y, h:i A') : 'N/A' }}</small>
            @endif
        </div>
    @endif

    @if($planner->isApproved())
        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-left: 5px solid #28a745 !important;">
            <h6 class="alert-heading font-weight-bold"><i class="icon-checkmark3 mr-2"></i>Scheme of Studies Approved</h6>
            <p class="mb-1 font-size-lg">This planner has been approved by the administration.</p>
            @if($planner->reviewer)
                <small class="text-muted">Approved by: <strong>{{ $planner->reviewer->name }}</strong> on {{ $planner->reviewed_at ? $planner->reviewed_at->format('d M Y, h:i A') : 'N/A' }}</small>
            @endif
        </div>
    @endif

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title font-weight-bold"><i class="icon-eye mr-2 text-info"></i>View Scheme of Studies Planner</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            {{-- Planner Header --}}
            <div class="text-center mb-4 p-3" style="background: #f8f9fa; border: 2px solid #dee2e6; border-radius: 8px;">
                <h4 class="font-weight-bold text-primary mb-1">{{ Qs::getSystemName() }}</h4>
                <h5 class="mb-2">Scheme of Studies for <strong>{{ $planner->planner_type_name }}</strong> (Session {{ $planner->session }})</h5>
                <h6 class="text-muted">
                    Class: <strong>{{ $planner->my_class->name }}</strong> |
                    Subject: <strong>{{ $planner->subject->name }}</strong> |
                    Teacher: <strong>{{ $planner->teacher->name ?? 'N/A' }}</strong>
                </h6>
                <div class="mt-2">{!! $planner->status_badge !!}</div>
            </div>

            {{-- Planner Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="bg-primary text-white">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th style="width: 150px;">Month</th>
                        <th>Chapters & Main Topics</th>
                        <th style="width: 250px;">Teaching Methods / Pedagogy</th>
                        <th style="width: 220px;">Assessment Strategy</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($planner->items as $key => $item)
                        <tr class="{{ $item->is_highlighted ? 'table-danger' : '' }}">
                            <td class="text-center font-weight-bold align-middle">{{ $key + 1 }}</td>
                            <td class="font-weight-bold align-middle">{{ $item->month_name }}</td>
                            <td>
                                <div class="font-weight-semibold">{{ $item->chapters }}</div>
                                @if($item->is_highlighted && $item->highlight_comment)
                                    <div class="mt-2 p-2 bg-danger-100" style="background: #f8d7da; border-radius: 4px; border-left: 3px solid #dc3545;">
                                        <small class="text-danger"><i class="icon-warning mr-1"></i><strong>Issue Flagged:</strong> {{ $item->highlight_comment }}</small>
                                    </div>
                                @endif
                            </td>
                            <td class="align-middle text-muted">{{ $item->teaching_methods ?: '-' }}</td>
                            <td class="align-middle text-muted">{{ $item->assessment ?: '-' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Action Buttons --}}
            <div class="text-center mt-4 pt-3" style="border-top: 2px solid #eee;">
                <a href="{{ route('planners.index') }}" class="btn btn-secondary mr-2">
                    <i class="icon-arrow-left7 mr-1"></i> Back to List
                </a>

                <a href="{{ route('planners.print', $planner->id) }}" target="_blank" class="btn btn-info mr-2">
                    <i class="icon-printer mr-1"></i> Print Official Document
                </a>

                @if($planner->canEdit() && (!Qs::userIsTeamSA() || $planner->teacher_id == Auth::user()->id))
                    <a href="{{ route('planners.edit', $planner->id) }}" class="btn btn-warning mr-2">
                        <i class="icon-pencil mr-1"></i> Edit Planner
                    </a>
                @endif

                @if($planner->isDraft() && (!Qs::userIsTeamSA() || $planner->teacher_id == Auth::user()->id))
                    <form method="POST" action="{{ route('planners.submit', $planner->id) }}" style="display:inline;">
                        @csrf @method('PUT')
                        <button type="submit" class="btn btn-primary mr-2"
                                onclick="return confirm('Submit this planner for principal approval?')">
                            <i class="icon-paperplane mr-1"></i> Submit to Principal / Admin
                        </button>
                    </form>
                @endif

                @if(Qs::userIsTeamSA() && $planner->isPending())
                    <a href="{{ route('planners.review', $planner->id) }}" class="btn btn-success">
                        <i class="icon-checkmark3 mr-1"></i> Review Planner
                    </a>
                @endif

                @if($planner->canDelete() || Qs::userIsTeamSA())
                    <form method="POST" action="{{ route('planners.destroy', $planner->id) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this planner? This action cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger ml-2">
                            <i class="icon-trash mr-1"></i> Delete
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
