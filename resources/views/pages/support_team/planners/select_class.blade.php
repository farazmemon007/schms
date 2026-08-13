@extends('layouts.master')
@section('page_title', 'Select Subject - '.$my_class->name)
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Select Subject for <strong>{{ $my_class->name }}</strong></h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            @if($subjects->count() > 0)
                <p class="text-muted mb-3">Select a subject to create/edit the scheme of studies planner:</p>
                <div class="row">
                    @foreach($subjects as $sub)
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('planners.create', [$my_class->id, $sub->id]) }}" class="text-decoration-none">
                                <div class="card border-left-3 border-left-primary shadow-sm" style="transition: all 0.3s; cursor: pointer;"
                                     onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'"
                                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)'">
                                    <div class="card-body text-center py-4">
                                        <i class="icon-book icon-2x text-primary mb-2 d-block"></i>
                                        <h6 class="mb-1 text-dark">{{ $sub->name }}</h6>
                                        <span class="text-muted font-size-sm">{{ $my_class->name }}</span>
                                        @if($sub->teacher)
                                            <br><small class="text-muted">Teacher: {{ $sub->teacher->name }}</small>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="icon-warning mr-2"></i>
                    No subjects assigned to you for <strong>{{ $my_class->name }}</strong>. Please contact admin.
                </div>
            @endif
        </div>
    </div>
@endsection
