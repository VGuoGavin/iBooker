@extends('layouts.dashboard.app')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">{{__('Bookings')}}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $booking->id }}</li>
@endsection

@section('content')
<input type="hidden" name="bid" value="{{ $booking->id }}">
<div id='mainContent'>
    <div class="row gap-20 pos-r">
    @if (Auth::user()->is_admin)
        @if ($booking->is_acknowledged)
            <div class="col-md-4">
        @else
            <div class="col-md-12">
        @endif
    @else
        <div class="col-md-6">
    @endif
            <div class="bd bgc-white h-100">
                <div class="layers">
                    <div class="layer peers bdB w-100 p-20">
                        <div class="peer mR-10">
                            <h5 class="mB-0">{{__('Booking')}}: {{ $booking->id }}</h5>
                        </div>
                        <div class="peer">
                            @if($booking->is_incomplete)
                                <span class="badge badge-pill badge-light text-danger">INCOMPLETE</span>
                            @elseif($booking->is_acknowledged)
                                <span class="badge badge-pill badge-secondary">PENDING</span>
                            @elseif($booking->is_accepted)
                                <span class="badge badge-pill badge-success mL-10">ACCEPTED</span>
                            @elseif($booking->is_rejected)
                                <span class="badge badge-pill badge-danger">REJECTED</span>
                            @endif
                        </div>
                    </div>
                    <div class="layer w-100 pY-10">
                        <table class="table-borderless">
                            <tbody>
                                @if (Auth::user()->is_admin)
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('Date of request')}}</th>
                                    <td class="pX-20 pY-10 va-t" >
                                        {{$booking->status_changed_at->format('d-M-Y')}}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('Requested by')}}</th>
                                    <td class="pX-20 pY-10 va-t" >
                                        {{$booking->details->booker->name}}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('Phone number')}}</th>
                                    <td class="pX-20 pY-10 va-t" >
                                        {{$booking->details->booker->phone}}
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('Building')}}</th>
                                    <td class="pX-20 pY-10 va-t" >
                                        {{$booking->details->room->building->name}}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('Room')}}</th>
                                    <td class="pX-20 pY-10 va-t" >
                                        {{$booking->details->room->name}}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('Start of use')}}</th>
                                    <td class="pX-20 pY-10 va-t" >
                                        {{$booking->details->start_datetime->format('d-M-Y H:i')}}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('End of use')}}</th>
                                    <td class="pX-20 pY-10 va-t" >
                                        {{$booking->details->end_datetime->format('d-M-Y H:i')}}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('Purpose')}}</th>
                                    <td class="pX-20 pY-10 va-t" >
                                        {{$booking->details->purpose}}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('Facilities required')}}</th>
                                    <td class="pX-20 pY-10 va-t">
                                        @forelse ( $booking->details->facilities as $facility )
                                            {{ $loop->first ? '' : ', '}}
                                            {{ ucwords(__($facility->name)) }}
                                        @empty
                                        -
                                        @endforelse
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('Other comments')}}</th>
                                    <td class="pX-20 pY-10 va-t" >{{ isset($booking->details->comments) ? $booking->details->comments : '-'}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @if (Auth::user()->is_admin && $booking->is_acknowledged)
        <div class="col-md-8">
            <div id='full-calendar'></div>
        </div>
    @endif
    @if (Auth::user()->is_admin)
        <div class="col-12 {{ !$booking->is_acknowledged ? 'order-first' : ''}}">
    @else
        <div class="col-md-6 order-md-last order-first">
    @endif
            <div class="bd bgc-white h-100">
                <div class="layers">
                @if($booking->is_incomplete)
                    <div class="layer bdB text-danger peers w-100 p-20">
                        <div class="peer peer-greed">
                            <h5 class="mB-0"><strong>Almost there!</strong></h5>
                        </div>
                    </div>
                    <div class="layer w-100 p-20">
                        <h5>Request a signature from a head of faculty or department by giving them the generated access code below. Once signed, refresh the page.</h5>
                        <div id="code-container" class="peers gap-20 ai-c mB-10 mT-20">
                            <h5 class="peer m-0">Access code: </h5>
                            <h5 id="code" class="peer m-0 bd pX-10 pY-5 fw-700 ta-c h-100" style="box-sizing: content-box; min-width: 75px; min-height: 26px">
                            </h5>
                            <span class="peer"><i class="loading-cog fas fa-circle-notch d-n"></i></span>
                            <small class="w-100 pY-5">Click button to generate access code</small>
                        </div>
                        <button type="button" id="generate" class="btn btn-primary">Generate</button>
                    </div>
                @elseif($booking->is_acknowledged)
                    @if (Auth::user()->is_admin)
                        <div class="layer bdB bg-dark c-white peers w-100 p-20">
                            <div class="peer peer-greed">
                                <h5 class="mB-0"><strong>ACTION</strong></h5>
                            </div>
                        </div>
                        <div class="layer w-100 p-20 h-100">
                            <h6>Signed by:</h6>
                            <table class="table table-borderless va-m">
                                <tbody>
                                    @foreach ($booking->signatures as $sgn)
                                        <tr>
                                            <th class="va-m whs-nw" scope="row">{{$sgn->signee->name}}</th>
                                            <td class="va-m whs-nw">on</td>
                                            <td class="va-m" style="width:99%">{{$sgn->created_at->format('d-M-Y H:i')}}</td>
                                            <td class="va-m whs-nw ta-r"><button type="button" class="btn btn-secondary verify">
                                                <span>Verify</span>
                                                <i class="loading-cog fas fa-circle-notch d-n"></i>
                                                <i class="far fa-check-circle d-n"></i>
                                                <i class="far fa-times-circle d-n"></i>
                                            </button>
                                                <input type="hidden" name="msg" value="{{base64_encode($sgn->message)}}">
                                                <input type="hidden" name="sid" value="{{base64_encode($sgn->signee->id)}}">
                                                <input type="hidden" name="sgn" value="{{base64_encode($sgn->signature)}}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="layer w-100 p-20">
                            <button type="button" id="accept" class="btn btn-success" data-toggle="modal" data-target="#acceptModal">ACCEPT</button>
                            <button type="button" id="reject" class="btn btn-light text-danger" data-toggle="modal" data-target="#rejectModal">REJECT</button>
                        </div>
                    @else
                        <div class="layer bdB bg-secondary c-white peers w-100 p-20">
                            <div class="peer peer-greed">
                                <h5 class="mB-0"><strong>PROCESSING</strong></h5>
                            </div>
                        </div>
                        <div class="layer w-100 p-20">
                            <p>Your booking request is being processed. A response should appear in 24 hours.</p>
                        </div>
                    @endif
                @elseif($booking->is_accepted)
                    <div class="layer bdB bg-success c-white peers w-100 p-20">
                        <div class="peer peer-greed">
                            <h5 class="mB-0"><strong>ACCEPTED</strong></h5>
                        </div>
                    </div>
                    @unless (Auth::user()->is_admin)
                        <div class="layer w-100 p-20">
                            <p>Hurray! Your booking request was <strong class="text-success">accepted</strong>.{{ isset($booking->admin_message) ? ' The admin left the following message:' : ''}}</p>
                            @isset($booking->admin_message)
                            <p><strong>{{$booking->admin_message}}</strong></p>
                            @endisset
                        </div>
                    @endunless
                @elseif($booking->is_rejected)
                    <div class="layer bdB bg-danger c-white peers w-100 p-20">
                        <div class="peer peer-greed">
                            <h5 class="mB-0"><strong>REJECTED</strong></h5>
                        </div>
                    </div>
                    @unless (Auth::user()->is_admin)
                        <div class="layer w-100 p-20">
                            <p>Your booking request was <strong class="text-danger">rejected</strong>.{{ isset($booking->admin_message) ? ' The admin left the following message:' : ''}}</p>
                            @isset($booking->admin_message)
                            <p><strong>{{$booking->admin_message}}</strong></p>
                            @endisset
                        </div>
                    @endunless
                @endif

                </div>
            </div>
        </div>
    </div>
</div>

@if (Auth::user()->is_admin && $booking->is_acknowledged)
<div class="modal fade" id="acceptModal" tabindex="-1" role="dialog" aria-labelledby="acceptModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="acceptModalTitle">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="layers">
                    <div class="layer w-100">
                        Once accepted, the booking request is <strong class="text-danger">unchangeable</strong>.
                    </div>
                    <div class="layer w-100 pY-20">
                        <div class="form-group">
                            <textarea class="form-control" name="additionalAccept" id="additionalAccept" rows="3" placeholder="Message for booker (optional)"></textarea>
                        </div>
                    </div>
                    <div class="layer w-100 pT-20">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="agreeAccept" id="agreeAccept" required>
                            <label class="custom-control-label" for="agreeAccept">I have <strong class="text-danger">accepted</strong> all the provided booking details.
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="acceptFinal" disabled>Accept</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalTitle">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="layers">
                    <div class="layer w-100">
                        Once rejected, the booking request is <strong class="text-danger">unchangeable</strong>.
                    </div>
                    <div class="layer w-100 pY-20">
                        <div class="form-group">
                            <textarea class="form-control" name="additionalReject" id="additionalReject" rows="3" placeholder="Message for booker (optional)"></textarea>
                        </div>
                    </div>
                    <div class="layer w-100 pT-20">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="agreeReject" id="agreeReject" required>
                            <label class="custom-control-label" for="agreeReject">I have <strong class="text-danger">acknowledged</strong> all the provided booking details and decided to <strong class="text-danger">reject</strong> it.
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="rejectFinal" disabled>Reject</button>
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
@endif
@endsection

@section('custom-script')
    @if (Auth::user()->is_admin && $booking->is_acknowledged)
    <script>
        $('#agreeAccept').change(function(e) {
            if($('#agreeAccept').is(":checked"))
                $('#acceptFinal').attr('disabled', false);
            else
                $('#acceptFinal').attr('disabled', 'disabled');
        });
        $('#agreeReject').change(function(e) {
            if($('#agreeReject').is(":checked"))
                $('#rejectFinal').attr('disabled', false);
            else
                $('#rejectFinal').attr('disabled', 'disabled');
        });
        $('.verify').click(function() {
            $this = $(this);
            console.log();
            $.ajax({
                method: 'post',
                url: '/api/checkSignature',
                data: $.param({
                    _token: $('meta[name=csrf-token]').attr('content'),
                    msg: $this.siblings('input[name=msg]').val(),
                    sgn: $this.siblings('input[name=sgn]').val(),
                    sid: $this.siblings('input[name=sid]').val(),
                }),
                beforeSend: function(xhr) {
                    $this.attr('disabled', 'disabled');
                    $this.siblings('small').remove();
                    $this.children('span').addClass('d-n');
                    $this.children('.loading-cog').removeClass('d-n');
                },
                success: function(data) {
                    $this.children('.loading-cog').addClass('d-n');
                    if(data.valid) {
                        $this.removeClass('btn-secondary').addClass('btn-success');
                        $this.children('.fa-check-circle').removeClass('d-n');
                    } else {
                        $this.removeClass('btn-secondary').addClass('btn-danger');
                        $this.children('.fa-times-circle').removeClass('d-n');
                    }
                    console.log(data);
                },
                error: function(xhr, message, error) {
                    $this.children('.loading-cog').addClass('d-n');
                    $this.children('span').removeClass('d-n');
                    $this.attr('disabled', false);
                    $this.before(`<small class="text-danger mR-5"><strong>An error occurred</strong></small>`);
                    console.log(xhr, message, error);
                }
            });
        });
        $('#acceptFinal').click(function(e) {
            $.ajax({
                method: 'post',
                url: '/api/accept',
                data: $.param({
                    _token: $('meta[name=csrf-token]').attr('content'),
                    bid: $('input[name=bid]').val(),
                    msg: $('textarea[name=additionalAccept]').val(),
                }),
                beforeSend: function(xhr) {
                    $('#acceptModal').modal('hide');
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
                        $('#status').text('ACCEPTED!');
                    } else {
                        $('#status-container').addClass('bg-danger c-white');
                        $('#status-error').removeClass('d-n');
                        $('#status').text('Signing failed. Please try again');
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
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
            });
        });
        $('#rejectFinal').click(function(e) {
            $.ajax({
                method: 'post',
                url: '/api/reject',
                data: $.param({
                    _token: $('meta[name=csrf-token]').attr('content'),
                    bid: $('input[name=bid]').val(),
                    msg: $('textarea[name=additionalReject]').val(),
                }),
                beforeSend: function(xhr) {
                    $('#rejectModal').modal('hide');
                    $('#processModal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                },
                success: async function(data) {
                    $('#status-pending').remove();
                    if (data.status){
                        $('#status-container').addClass('bg-danger c-white');
                        $('#status-success').removeClass('d-n');
                        $('#status').text('REJECTED');
                    } else {
                        $('#status-container').addClass('bg-danger c-white');
                        $('#status-error').removeClass('d-n');
                        $('#status').text('Signing failed. Please try again');
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
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
            });
        });
        (function(){
            let bookings = `{!! $bookings->toJSON() !!}`;
            bookings = JSON.parse(bookings);
            bookings.forEach(booking => {
                $('#full-calendar').fullCalendar('renderEvent', {
                    title: booking.details.purpose,
                    start: booking.details.start_datetime,
                    end: booking.details.end_datetime,
                    description: `${booking.details.start_datetime.slice(0,-3)} - ${booking.details.end_datetime.slice(0,-3)}`,
                }, true);
            });
        })();
    </script>
    @elseif($booking->is_incomplete)
    <script>
        var x;
        $('#generate').click(function() {
            $.ajax({
                method: 'post',
                url: '/api/accessCode',
                data: $.param({
                    _token: $('meta[name=csrf-token]').attr('content'),
                    bid: $('input[name=bid]').val(),
                }),
                beforeSend: function(xhr) {
                    $('#generate').attr('disabled', 'disabled');
                    $('#code-container small').remove();
                    $('#code').text('');
                    $('#code-container .loading-cog').removeClass('d-n').addClass('d-ib');
                    clearInterval(x);
                },
                success: function(data) {
                    console.log(data);
                    $('#code').text(data.code);
                    $('#code-container .loading-cog').removeClass('d-ib').addClass('d-n');
                    var eventTime= data.expiry;
                    var currentTime = Math.floor(Date.now() / 1000);
                    var diffTime = eventTime - currentTime;
                    var duration = moment.duration(diffTime*1000, 'milliseconds');
                    var interval = 1000;
                    $('#code-container').append(`<small class="w-100 pY-5">Code will expire in <span class="countdown"></span></small>`);
                    x = setInterval(async function(){
                        duration = moment.duration(duration - interval, 'milliseconds');
                        console.log(moment.utc(duration.as('milliseconds')).format('HH:mm:ss'));
                        $('.countdown').text(moment.utc(duration.as('milliseconds')).format('HH:mm:ss'));
                        if(duration.seconds() < 0) {
                            $('#generate').attr('disabled', false);
                            clearInterval(x);
                            await $('#code-container small').remove();
                            $('#code-container').append(`<small class="w-100 pY-5 text-danger"><strong>Expired. Please generate another code</strong></small>`);
                        }
                    }, interval);
                },
                error: function(xhr, message, error) {
                    console.log(xhr, message, error);
                    $('#generate').attr('disabled', false);
                    $('#code-container .loading-cog').removeClass('d-ib').addClass('d-n');
                    $('#code-container').append(`<small class="w-100 pY-5 text-danger"><strong>Failed to obtain access code. Please try again</strong></small>`);
                }
            });
        })
    </script>
    @endif
@endsection
