@extends('layouts.master')
@section('page_title', 'Create Planner - '.$my_class->name.' - '.$subject->name)
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title font-weight-bold"><i class="icon-plus-circle2 mr-2 text-primary"></i>Create Scheme of Studies Planner</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('planners.store') }}" id="plannerForm">
                @csrf
                <input type="hidden" name="my_class_id" value="{{ $my_class->id }}">
                <input type="hidden" name="subject_id" value="{{ $subject->id }}">

                {{-- Planner Header --}}
                <div class="text-center mb-4 p-3" style="background: #f8f9fa; border: 2px solid #dee2e6; border-radius: 8px;">
                    <h4 class="font-weight-bold text-primary mb-1">{{ Qs::getSystemName() }}</h4>
                    <h5 class="mb-2">
                        Scheme of Studies for
                        <select name="planner_type" class="d-inline-block form-control" style="width: auto; display: inline-block !important; font-size: inherit; font-weight: bold; border: 1px dashed #007bff; padding: 2px 8px;">
                            <option value="mid_term">Mid Term</option>
                            <option value="final_term">Final Term</option>
                        </select>
                        (Session {{ $current_session }})
                    </h5>
                    <h6 class="text-muted">Class: <strong>{{ $my_class->name }}</strong> | Subject: <strong>{{ $subject->name }}</strong></h6>
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
                        @foreach($months as $key => $month)
                            <tr class="planner-row">
                                <td class="text-center font-weight-bold row-number align-middle">{{ $key + 1 }}</td>
                                <td class="align-middle">
                                    <input type="text" name="months[]" value="{{ $month }}" class="form-control form-control-sm font-weight-bold" placeholder="Month">
                                </td>
                                <td>
                                    <textarea name="chapters[]" class="form-control form-control-sm" rows="2" placeholder="Enter chapter names & main topics (e.g. Chapter #01 - Life Processes)"></textarea>
                                </td>
                                <td>
                                    <input type="text" name="teaching_methods[]" class="form-control form-control-sm" placeholder="e.g. Lecture, Activity, Lab Work">
                                </td>
                                <td>
                                    <input type="text" name="assessments[]" class="form-control form-control-sm" placeholder="e.g. Monthly Test, Quiz, Assignment">
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-sm btn-danger remove-row" title="Remove Row">
                                        <i class="icon-cross2"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
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
                            onclick="return confirm('Are you sure you want to submit this planner for admin approval? You won\'t be able to edit it until admin reviews it.')">
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
    // Add new row
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

    // Remove row
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

    // Form validation
    $('#plannerForm').submit(function(e) {
        var hasData = false;
        $('textarea[name="chapters[]"]').each(function() {
            if ($(this).val().trim() !== '') {
                hasData = true;
            }
        });

        if (!hasData) {
            e.preventDefault();
            alert('Please fill in at least one chapter entry before saving.');
            return false;
        }
    });
});
</script>
@endsection
