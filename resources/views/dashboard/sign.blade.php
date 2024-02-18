@extends('layouts.dashboard.app')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ __('Sign') }}</li>
@endsection

@section('content')
<input type="hidden" name="bid">
<div id='mainContent'>
    <div class="row gap-20 pos-r">
        <div id="access" class="col-12">
            <div class="bd bgc-white h-100">
                <div class="layers">
                    <div class="layer peers bdB w-100 p-20" id="code-container">
                        <div class="form-inline peer-greed">
                            <div class="form-group">
                                <label for=""><h5 class="m-0 mR-20">ACCESS CODE:</h5></label>
                                <input type="text" name="code" id="code" class="ta-c form-control w-100p" style="width: 100px" placeholder="XXXXXX" maxlength="6">
                            </div>
                            <div class="form-group mL-10">
                                <button type="button" class="btn btn-secondary" id="search" disabled><i class="ti-search" aria-label="Go"></i><i class="loading-cog fas fa-circle-notch d-n"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="details" class="col-12" style="display: none">
            <div class="bd bgc-white h-100">
                <div class="layers">
                    <div class="layer peers bdB w-100 p-20">
                        <div class="peer">
                            <h5 class="mB-0">{{__('Booking')}}: <span id="booking-id">###</span></h5>
                        </div>
                    </div>
                    <div class="layer w-100 pY-10">
                        <table class="table-borderless">
                            <tbody>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('Building')}}</th>
                                    <td class="pX-20 pY-10 va-t" id="building">
                                        ###
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('Room')}}</th>
                                    <td class="pX-20 pY-10 va-t" id="room">
                                        ###
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('Start use')}}</th>
                                    <td class="pX-20 pY-10 va-t" id="start-datetime">
                                        ###
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('End use')}}</th>
                                    <td class="pX-20 pY-10 va-t" id="end-datetime">
                                        ###
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('Purpose')}}</th>
                                    <td class="pX-20 pY-10 va-t" id="purpose">
                                        ###
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('Facilities required')}}</th>
                                    <td class="pX-20 pY-10 va-t" id="facilities">
                                        ###
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('Other comments')}}</th>
                                    <td class="pX-20 pY-10 va-t" id="comments">###</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="layer bdT peers w-100 p-20">
                        <div class="peer">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#signModal"><i class="ti-ink-pen fsz-sm"></i> Sign booking</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="signModal" tabindex="-1" role="dialog" aria-labelledby="commitModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commitModalTitle">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <div class="modal-body">
            <div class="layers">
                <div class="layer w-100">
                    Once signed, the booking request is <strong class="text-danger">unchangeable</strong> and will be sent to the admins to be processed.
                </div>
                <div class="layer w-100 pT-20">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="agree" id="agree" required>
                        <label class="custom-control-label" for="agree">I have <strong class="text-danger">acknowledged</strong> all the shown booking details.
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="sign" disabled>Sign</button>
        </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="processModal" tabindex="-1" role="dialog" aria-labelledby="processTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" id="status-container">
            <div class="modal-body d-f ai-c jc-c">
                <i class="loading-cog fas fa-circle-notch fsz-lg" id="status-pending"></i>
                <i class="far fa-times-circle fsz-lg d-n" id="status-error"></i>
                <i class="far fa-check-circle fsz-lg d-n" id="status-success"></i>
                <h5 class="d-ib m-0 mL-20" id="status">Processing</h5>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-script')
<script>
    $('#agree').change(function(e) {
        if($('#agree').is(":checked"))
            $('#sign').attr('disabled', false);
        else
            $('#sign').attr('disabled', 'disabled');
    });
    $('#code').keydown(function(e) {
        e.preventDefault();
        let val = $('#code').val();
        if(e.key.match(/^[a-z0-9]$/i)) {
            if(val.length < 6) val += e.key.toUpperCase();
            $('#code').val(val);
            $('#search').attr('disabled', val.length < 6 ? 'disabled' : false);
        } else if(e.key == 'Enter') {
            $('#search').click();
        } else if(e.key == 'Backspace') {
            val = val.slice(0, -1);
            $('#code').val(val);
            $('#search').attr('disabled', val.length < 6 ? 'disabled' : false);
        } else if(e.key == 'Escape') {
            $('#code').blur();
        }
    });
    $('#search').click(function(e) {
        $.ajax({
            method: 'post',
            url: '/api/accessBooking',
            data: $.param({
                _token: $('meta[name=csrf-token]').attr('content'),
                code: $('#code').val(),
            }),
            beforeSend: function(xhr) {
                $('#code').attr('disabled', 'disabled');
                $('#search').attr('disabled', 'disabled');
                $('#search .loading-cog').removeClass('d-n');
                $('#search .ti-search').addClass('d-n');
                $('#code-container small').remove();
            },
            success: async function(data) {
                $('#access').slideUp(500, function() {
                    $('#access').remove();
                });
                $('input[name=bid]').val(data.id);
                $('#booking-id').text(data.id);
                $('#building').text(data.details.building.name);
                $('#room').text(data.details.room.name);
                $('#start-datetime').text(data.details.start);
                $('#end-datetime').text(data.details.end);
                $('#purpose').text(data.details.purpose);
                $('#facilities').text(data.details.facilities.map((f) => f.name).join(', '));
                $('#comments').text(data.details.comments);
                $('#details').slideDown(500);
            },
            error: function(xhr, message, error) {
                $('#code').attr('disabled', false);
                $('#search').attr('disabled', false);
                $('#search .loading-cog').addClass('d-n');
                $('#search .ti-search').removeClass('d-n');
                if(xhr.status == 404)
                    $('#code-container').append(`<small class="w-100 pT-10"><strong class="text-danger">Not found</strong></small>`);
                else
                    $('#code-container').append(`<small class="w-100 pT-10"><strong class="text-danger">Request failed. Please try again</strong></small>`);
                $('#code').focus();
            }
        });
    });
    $('#sign').click(function(e) {
        $.ajax({
            method: 'post',
            url: '/api/sign',
            data: $.param({
                _token: $('meta[name=csrf-token]').attr('content'),
                bid: $('input[name=bid]').val(),
            }),
            beforeSend: function(xhr) {
                $('#signModal').modal('hide');
                $('#processModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: async function(data) {
                $('#status-pending').remove();
                if (data.status){
                    $('#status-container').addClass('bg-success c-white');
                    $('#status-success').removeClass('d-n');
                    $('#status').text('Success!');
                } else {
                    $('#status-container').addClass('bg-danger c-white');
                    $('#status-error').removeClass('d-n');
                    $('#status').text('Signature invalid. Please try again');
                }
                setTimeout(function() {
                    location.reload();
                }, 5000);
            },
            error: function(xhr, message, error) {
                console.log(xhr);
                $('#status-pending').remove();
                $('#status-container').addClass('bg-danger c-white');
                $('#status-error').removeClass('d-n');
                $('#status').text('An error occurred. Please try again');
            }
        });
    })
</script>
@endsection
