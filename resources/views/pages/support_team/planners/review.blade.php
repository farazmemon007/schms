@extends('layouts.master')
@section('page_title', 'Review Planner - '.$planner->my_class->name.' - '.$planner->subject->name)
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

    {{-- Header Banner --}}
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title font-weight-bold"><i class="icon-checkmark3 mr-2 text-success"></i>Review Scheme of Studies Planner</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="text-center mb-4 p-3" style="background: #f8f9fa; border: 2px solid #dee2e6; border-radius: 8px;">
                <h4 class="font-weight-bold text-primary mb-1">{{ Qs::getSystemName() }}</h4>
                <h5 class="mb-2">Planner for <strong>{{ $planner->planner_type_name }}</strong> (Session {{ $planner->session }})</h5>
                <h6 class="text-muted">
                    Class: <strong>{{ $planner->my_class->name }}</strong> |
                    Subject: <strong>{{ $planner->subject->name }}</strong> |
                    Submitted By Teacher: <strong>{{ $planner->teacher->name ?? 'N/A' }}</strong>
                </h6>
                <div class="mt-2">{!! $planner->status_badge !!}</div>
            </div>

            {{-- Rejection Form Wraps the Table to Collect Highlighted Items --}}
            <form method="POST" action="{{ route('planners.reject', $planner->id) }}" id="rejectForm">
                @csrf @method('PUT')

                <div class="alert alert-info border-0 shadow-sm mb-3" style="border-left: 4px solid #17a2b8 !important;">
                    <i class="icon-info22 mr-2"></i>
                    <strong>Review Instructions:</strong> Inspect the monthly chapters below. If any row needs correction, check <strong>"Flag Issue in Row"</strong> and specify what needs fixing. If sending back for revision, enter overall principal remarks and click <strong>"Reject & Send Back"</strong>. Otherwise, click <strong>"Approve Planner"</strong>.
                </div>

                {{-- Planner Items Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-primary text-white">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 130px;">Month</th>
                            <th>Chapters & Main Topics</th>
                            <th style="width: 220px;">Teaching Methods</th>
                            <th style="width: 200px;">Assessment</th>
                            <th style="width: 240px;">Flag Specific Issue</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($planner->items as $key => $item)
                            <tr class="item-row {{ $item->is_highlighted ? 'table-danger' : '' }}" id="row-{{ $item->id }}">
                                <td class="text-center font-weight-bold align-middle">{{ $key + 1 }}</td>
                                <td class="font-weight-bold align-middle">{{ $item->month_name }}</td>
                                <td>
                                    <div class="font-weight-semibold">{{ $item->chapters }}</div>
                                    @if($item->topics)
                                        <div class="small text-muted mt-1"><strong>Details:</strong> {{ $item->topics }}</div>
                                    @endif
                                </td>
                                <td class="align-middle text-muted small">{{ $item->teaching_methods ?: '-' }}</td>
                                <td class="align-middle text-muted small">{{ $item->assessment ?: '-' }}</td>
                                <td class="align-middle">
                                    <div class="custom-control custom-checkbox mb-1">
                                        <input type="checkbox"
                                               class="custom-control-input flag-checkbox"
                                               id="flag_{{ $item->id }}"
                                               name="highlighted_items[]"
                                               value="{{ $item->id }}"
                                               {{ $item->is_highlighted ? 'checked' : '' }}>
                                        <label class="custom-control-label text-danger font-weight-semibold" for="flag_{{ $item->id }}">
                                            Flag Issue in Row
                                        </label>
                                    </div>
                                    <div class="comment-box {{ $item->is_highlighted ? '' : 'd-none' }}" id="comment_box_{{ $item->id }}">
                                        <input type="text"
                                               name="highlight_comments[{{ $item->id }}]"
                                               value="{{ $item->highlight_comment }}"
                                               class="form-control form-control-sm border-danger"
                                               placeholder="Specify what needs correction...">
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Rejection Remarks Modal --}}
                <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" id="rejectModalLabel"><i class="icon-warning mr-2"></i>Reject & Send Back to Teacher</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p class="text-muted">Please provide clear remarks for the teacher detailing what needs to be updated or revised before resubmission:</p>
                                <div class="form-group">
                                    <label for="admin_remarks" class="font-weight-bold">Principal / Admin Remarks <span class="text-danger">*</span></label>
                                    <textarea name="admin_remarks" id="admin_remarks" class="form-control" rows="4" placeholder="Enter remarks for the teacher (e.g. Please revise November topics and add missing chapters)...">{{ $planner->admin_remarks }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="icon-paperplane mr-1"></i> Confirm Rejection
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>

            {{-- Form for Approval --}}
            <form method="POST" action="{{ route('planners.approve', $planner->id) }}" id="approveForm" style="display:none;">
                @csrf @method('PUT')
            </form>

            {{-- Action Buttons --}}
            <div class="text-center mt-4 pt-3" style="border-top: 2px solid #eee;">
                <a href="{{ route('planners.admin') }}" class="btn btn-secondary mr-2">
                    <i class="icon-arrow-left7 mr-1"></i> Back to List
                </a>

                <a href="{{ route('planners.print', $planner->id) }}" target="_blank" class="btn btn-info mr-2">
                    <i class="icon-printer mr-1"></i> Print Preview
                </a>

                {{-- Reject Trigger Button --}}
                <button type="button" class="btn btn-danger mr-2" data-toggle="modal" data-target="#rejectModal">
                    <i class="icon-cross2 mr-1"></i> Reject & Send Back
                </button>

                {{-- Approve Button --}}
                <button type="button" class="btn btn-success" id="btnApprove">
                    <i class="icon-checkmark3 mr-1"></i> Approve Planner
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('.flag-checkbox').change(function() {
        var itemId = $(this).val();
        var isChecked = $(this).is(':checked');
        var $row = $('#row-' + itemId);
        var $commentBox = $('#comment_box_' + itemId);

        if (isChecked) {
            $row.addClass('table-danger');
            $commentBox.removeClass('d-none');
        } else {
            $row.removeClass('table-danger');
            $commentBox.addClass('d-none');
        }
    });

    $('#btnApprove').click(function() {
        if (confirm('Are you sure you want to APPROVE this planner?')) {
            $('#approveForm').submit();
        }
    });

    $('#rejectForm').submit(function(e) {
        var remarks = $('#admin_remarks').val().trim();
        if (remarks.length < 5) {
            e.preventDefault();
            alert('Please provide detailed admin remarks (at least 5 characters) before rejecting.');
            return false;
        }
    });
});
</script>
@endsection
