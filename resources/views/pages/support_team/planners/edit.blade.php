@extends('layouts.master')
@section('page_title', 'Edit Planner - '.$planner->my_class->name.' - '.$planner->subject->name)
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

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title font-weight-bold"><i class="icon-pencil7 mr-2 text-warning"></i>Edit Scheme of Studies Planner</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('planners.update', $planner->id) }}" id="plannerForm">
                @csrf @method('PUT')
                <input type="hidden" name="my_class_id" value="{{ $planner->my_class_id }}">
                <input type="hidden" name="subject_id" value="{{ $planner->subject_id }}">

                {{-- Planner Header --}}
                <div class="text-center mb-4 p-3" style="background: #f8f9fa; border: 2px solid #dee2e6; border-radius: 8px;">
                    <h4 class="font-weight-bold text-primary mb-1">{{ Qs::getSystemName() }}</h4>
                    <h5 class="mb-2">
                        Scheme of Studies for
                        <select name="planner_type" class="d-inline-block form-control" style="width: auto; display: inline-block !important; font-size: inherit; font-weight: bold; border: 1px dashed #007bff; padding: 2px 8px;">
                            <option value="mid_term" {{ $planner->planner_type == 'mid_term' ? 'selected' : '' }}>Mid Term</option>
                            <option value="final_term" {{ $planner->planner_type == 'final_term' ? 'selected' : '' }}>Final Term</option>
                        </select>
                        (Session {{ $planner->session }})
                    </h5>
                    <h6 class="text-muted">Class: <strong>{{ $planner->my_class->name }}</strong> | Subject: <strong>{{ $planner->subject->name }}</strong></h6>
                    <div class="mt-2">{!! $planner->status_badge !!}</div>
                </div>

                {{-- Planner Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="plannerTable">
                        <thead class="bg-primary text-white">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 150px;">Month</th>
                            <th>Chapters & Main Topics <span class="text-warning">*</span></th>
                            <th style="width: 250px;">Teaching Methods / Pedagogy</th>
                            <th style="width: 220px;">Assessment Strategy</th>
                            <th style="width: 50px;">
                                <button type="button" class="btn btn-sm btn-success" id="addRow" title="Add Row">
                                    <i class="icon-plus2"></i>
                                </button>
                            </th>
                        </tr>
                        </thead>
                        <tbody id="plannerBody">
                        @forelse($planner->items as $key => $item)
                            <tr class="planner-row {{ $item->is_highlighted ? 'table-danger' : '' }}">
                                <td class="text-center font-weight-bold row-number align-middle">{{ $key + 1 }}</td>
                                <td class="align-middle">
                                    <input type="text" name="months[]" value="{{ $item->month_name }}" class="form-control form-control-sm font-weight-bold" placeholder="Month">
                                </td>
                                <td>
                                    <textarea name="chapters[]" class="form-control form-control-sm" rows="2" placeholder="Enter chapter names & main topics">{{ $item->chapters }}</textarea>
                                    @if($item->is_highlighted && $item->highlight_comment)
                                        <div class="mt-2 p-2 bg-danger-100" style="background: #f8d7da; border-radius: 4px; border-left: 3px solid #dc3545;">
                                            <small class="text-danger"><i class="icon-warning mr-1"></i><strong>Issue Flagged by Admin:</strong> {{ $item->highlight_comment }}</small>
                                        </div>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <input type="text" name="teaching_methods[]" value="{{ $item->teaching_methods }}" class="form-control form-control-sm" placeholder="Teaching methods">
                                </td>
                                <td class="align-middle">
                                    <input type="text" name="assessments[]" value="{{ $item->assessment }}" class="form-control form-control-sm" placeholder="Assessment strategy">
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-sm btn-danger remove-row" title="Remove Row">
                                        <i class="icon-cross2"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            @foreach($months as $key => $month)
                                <tr class="planner-row">
                                    <td class="text-center font-weight-bold row-number align-middle">{{ $key + 1 }}</td>
                                    <td class="align-middle">
                                        <input type="text" name="months[]" value="{{ $month }}" class="form-control form-control-sm font-weight-bold" placeholder="Month">
                                    </td>
                                    <td>
                                        <textarea name="chapters[]" class="form-control form-control-sm" rows="2" placeholder="Enter chapter names & topics"></textarea>
                                    </td>
                                    <td class="align-middle">
                                        <input type="text" name="teaching_methods[]" class="form-control form-control-sm" placeholder="Teaching methods">
                                    </td>
                                    <td class="align-middle">
                                        <input type="text" name="assessments[]" class="form-control form-control-sm" placeholder="Assessment strategy">
                                    </td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-sm btn-danger remove-row" title="Remove Row">
                                            <i class="icon-cross2"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Action Buttons --}}
                <div class="text-center mt-4 pt-3" style="border-top: 2px solid #eee;">
                    <a href="{{ route('planners.index') }}" class="btn btn-secondary mr-2">
                        <i class="icon-arrow-left7 mr-1"></i> Cancel
                    </a>
                    <button type="submit" name="action" value="save" class="btn btn-warning mr-2">
                        <i class="icon-floppy-disk mr-1"></i> Save as Draft
                    </button>
                    <button type="submit" name="action" value="submit" class="btn btn-primary"
                            onclick="return confirm('Are you sure you want to resubmit this planner for principal approval?')">
                        <i class="icon-paperplane mr-1"></i> Submit to Principal / Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#addRow').click(function() {
        var rowCount = $('#plannerBody tr').length + 1;
        var newRow = '<tr class="planner-row">' +
            '<td class="text-center font-weight-bold row-number align-middle">' + rowCount + '</td>' +
            '<td class="align-middle"><input type="text" name="months[]" class="form-control form-control-sm font-weight-bold" placeholder="Month"></td>' +
            '<td><textarea name="chapters[]" class="form-control form-control-sm" rows="2" placeholder="Enter chapter names & main topics"></textarea></td>' +
            '<td><input type="text" name="teaching_methods[]" class="form-control form-control-sm" placeholder="Teaching methods"></td>' +
            '<td><input type="text" name="assessments[]" class="form-control form-control-sm" placeholder="Assessment strategy"></td>' +
            '<td class="text-center align-middle"><button type="button" class="btn btn-sm btn-danger remove-row" title="Remove Row"><i class="icon-cross2"></i></button></td>' +
            '</tr>';
        $('#plannerBody').append(newRow);
        updateRowNumbers();
    });

    $(document).on('click', '.remove-row', function() {
        if ($('#plannerBody tr').length > 1) {
            $(this).closest('tr').remove();
            updateRowNumbers();
        } else {
            alert('You must have at least one row.');
        }
    });

    function updateRowNumbers() {
        $('#plannerBody tr').each(function(index) {
            $(this).find('.row-number').text(index + 1);
        });
    }

    $('#plannerForm').submit(function(e) {
        var hasData = false;
        $('textarea[name="chapters[]"]').each(function() {
            if ($(this).val().trim() !== '') {
                hasData = true;
            }
        });
        if (!hasData) {
            e.preventDefault();
            alert('Please fill in at least one chapter entry.');
            return false;
        }
    });
});
</script>
@endsection
