@extends('layouts.master')
@section('page_title', 'Consolidated Scheme of Studies')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title font-weight-bold"><i class="icon-grid5 mr-2 text-primary"></i>Consolidated Scheme of Studies</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            {{-- Filter Form --}}
            <form method="GET" action="{{ route('planners.consolidated') }}" class="mb-4 p-3" style="background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef;">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label for="class_id" class="font-weight-bold">Select Class <span class="text-danger">*</span></label>
                        <select name="class_id" id="class_id" class="form-control select-search" onchange="this.form.submit()">
                            @foreach($my_classes as $mc)
                                <option value="{{ $mc->id }}" {{ $selected_class_id == $mc->id ? 'selected' : '' }}>{{ $mc->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="planner_type" class="font-weight-bold">Term</label>
                        <select name="planner_type" id="planner_type" class="form-control select" onchange="this.form.submit()">
                            <option value="mid_term" {{ $selected_term == 'mid_term' ? 'selected' : '' }}>Mid Term</option>
                            <option value="final_term" {{ $selected_term == 'final_term' ? 'selected' : '' }}>Final Term</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="session" class="font-weight-bold">Session</label>
                        <input type="text" name="session" id="session" class="form-control" value="{{ $selected_session }}">
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="icon-filter3 mr-1"></i> Filter
                        </button>
                    </div>
                </div>
            </form>

            @if($my_class && $planners->count() > 0)
                <div class="text-center mb-4 p-3" style="background: #eef2f7; border-radius: 8px; border-left: 4px solid #007bff;">
                    <h4 class="font-weight-bold text-primary mb-1">{{ Qs::getSystemName() }}</h4>
                    <h5 class="mb-1">Consolidated Scheme of Studies - <strong>{{ $selected_term == 'mid_term' ? 'Mid Term' : 'Final Term' }}</strong> (Session {{ $selected_session }})</h5>
                    <h6 class="text-muted">Class: <strong>{{ $my_class->name }}</strong> | Approved Subjects Count: <strong>{{ $planners->count() }}</strong></h6>
                </div>

                {{-- Consolidated Grid Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-primary text-white text-center">
                        <tr>
                            <th style="width: 120px;">Month</th>
                            @foreach($planners as $p)
                                <th>
                                    <div>{{ $p->subject->name ?? 'Subject' }}</div>
                                    <small class="font-weight-normal text-white-50">Teacher: {{ $p->teacher->name ?? 'N/A' }}</small>
                                </th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($months as $month)
                            <tr>
                                <td class="font-weight-bold bg-light align-middle text-center">{{ $month }}</td>
                                @foreach($planners as $p)
                                    @php
                                        $numericMonth = date('n', strtotime($month));
                                        $item = $p->items->first(function($i) use ($month, $numericMonth) {
                                            return strcasecmp(trim($i->month), $month) === 0 || $i->month == $numericMonth;
                                        });
                                    @endphp
                                    <td class="align-top" style="min-width: 200px;">
                                        @if($item && !empty(trim($item->chapters)))
                                            <div class="font-weight-semibold text-dark">{{ $item->chapters }}</div>
                                            @if($item->topics)
                                                <small class="text-muted d-block mt-1"><strong>Topics:</strong> {{ $item->topics }}</small>
                                            @endif
                                            @if($item->teaching_methods)
                                                <small class="text-info d-block mt-1"><strong>Methods:</strong> {{ $item->teaching_methods }}</small>
                                            @endif
                                            @if($item->assessment)
                                                <small class="text-success d-block mt-1"><strong>Assessment:</strong> {{ $item->assessment }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted font-italic">-</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Print Action --}}
                <div class="text-center mt-4 pt-3" style="border-top: 2px solid #eee;">
                    <button type="button" onclick="window.print()" class="btn btn-info">
                        <i class="icon-printer mr-1"></i> Print Consolidated Scheme
                    </button>
                </div>
            @else
                <div class="alert alert-warning text-center py-4">
                    <i class="icon-warning icon-2x mb-2 d-block text-warning"></i>
                    <h5>No Approved Scheme of Studies Planners Found</h5>
                    <p class="mb-0">There are no approved planners for <strong>{{ $my_class->name ?? 'selected class' }}</strong> in {{ $selected_term == 'mid_term' ? 'Mid Term' : 'Final Term' }} (Session {{ $selected_session }}).</p>
                </div>
            @endif
        </div>
    </div>
@endsection
