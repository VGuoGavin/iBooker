@extends('layouts.dashboard.app')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">{{__('Bookings')}}</a></li>
<li class="breadcrumb-item"><a href="{{ route('drafts.show', ['id' => $draft->trimmed_id]) }}">{{ $draft->id }}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
@endsection

@section('content')
<div id='mainContent'>
    <div class="row gap-20 pos-r">
        <div class="col-md-4">
            <div class="bd bgc-white h-100">
                <div class="layers">
                    <div class="layer w-100 pX-20 pT-20">
                        <h6 class="lh-1 font-weight-bold">{{ $draft->id }} (edit)</h6>
                    </div>
                    <div class="layer w-100 p-20">
                        <form id="bookingForm" action="{{ route('drafts.update', ['id' => $draft->trimmed_id]) }}" method="POST">
                            @csrf
                            @method('put')
                            <div class="form-group">
                                <label for="building">Building <span class="c-red-500">*</span></label>
                                <select class="custom-select" name="building" id="building">
                                    @isset($draft->room)
                                        <option value="">Select one</option>
                                        @foreach ($buildings as $building)
                                            <option value="{{$building->id}}" {{$draft->room->building->id == $building->id ? 'selected' : ''}}>{{$building->name}}</option>
                                        @endforeach
                                    @else
                                        <option value="" selected>Select one</option>
                                        @foreach ($buildings as $building)
                                            <option value="{{$building->id}}">{{$building->name}}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="room">Room <span class="c-red-500">*</span>
                                    <i class="loading-cog fas fa-circle-notch d-n"></i>
                                </label>
                                <select class="custom-select" name="room" id="room" {{ isset($draft->room) ? '' : 'disabled'}}>
                                    @isset($draft->room)
                                        @foreach ($draft->room->building->rooms as $room)
                                            <option value="{{$room->id}}" {{$draft->room->id == $room->id ? 'selected' : ''}}>{{$room->name}}</option>
                                        @endforeach
                                    @else
                                        <option value="" selected>Select a building first</option>
                                    @endisset
                                </select>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="startDateTime">Start datetime <span class="c-red-500">*</span></label>
                                    <input class="form-control datetimepicker-input" type="datetime" name="startDateTime" id="startDateTime" class="form-control" placeholder="Select a date & time" data-toggle="datetimepicker" data-target="#startDateTime">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="endDateTime">End datetime <span class="c-red-500">*</span></label>
                                    <input class="form-control datetimepicker-input" type="datetime" name="endDateTime" id="endDateTime" class="form-control" placeholder="Select a date & time"  data-toggle="datetimepicker" data-target="#endDateTime">
                                </div>
                                <div class="form-group col-12">
                                    <small>Make sure picked dates are far enough from today in order to be processed</small>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12 facilities-container">
                                    <label class="d-b" for="facilities">Facilities required
                                        <i class="mL-5 loading-cog fas fa-circle-notch d-n"></i>
                                    </label>
                                    @isset($draft->room)
                                        @foreach ($draft->room->facilities as $facility)
                                            <div class="custom-control custom-checkbox">
                                                @if($draft->facilities()->where('id', $facility->id)->first() !== null)
                                                <input type="checkbox" class="custom-control-input" name="facility[{{$facility->id}}]" id="facility-{{$facility->id}}" checked>
                                                @else
                                                <input type="checkbox" class="custom-control-input" name="facility[{{$facility->id}}]" id="facility-{{$facility->id}}">
                                                @endisset
                                                <label class="custom-control-label" for="facility-{{$facility->id}}">{{$facility->name}}</label>
                                            </div>
                                        @endforeach
                                    @else
                                        <small>Select a room to view its facilities</small>
                                    @endisset
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="purpose">{{__('Purpose')}} <span class="c-red-500">*</span></label>
                                <input value="{{$draft->purpose}}" type="text" class="form-control" name="purpose" id="purpose" placeholder="">
                            </div>
                            <div class="form-group">
                                <label for="comment">{{__('Other comments')}}</label>
                                <textarea class="form-control" name="comment" id="comment" rows="3">{{$draft->comments}}</textarea>
                            </div>
                            <button class="btn btn-dark" type="submit">
                                <i class="ti-save fsz-xs mR-5"></i>
                                {{__('Save draft')}}
                            </button>
                            <a class="btn btn-link c-red-500" role="button" href="{{ route('drafts.show', ['id' => $draft->trimmed_id]) }}">
                                {{__('Cancel')}}
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div id='full-calendar'></div>
            <div id="calendar-overlay" class="position-absolute op-40p bgc-white w-100 h-100 t-0 l-0 d-n ai-c jc-c" style="z-index: 100">
                <i class="loading-cog fas fa-circle-notch fa-4x"></i>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-script')
<script>
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
    $(window).bind('beforeunload', function(e) {
        return '';
    });
    $('#bookingForm').bind('submit', function() {
        $(window).unbind('beforeunload');
    });
    $('#building').change(function(e) {
        var b_id = $('#building').val();
        var data = {
            _token: $('meta[name=csrf-token]').attr('content'),
            b_id: b_id,
        };
        console.log(data);
        if(b_id) {
            $.ajax({
                url: '/api/roomsInBuilding',
                method: 'post',
                data: $.param(data),
                beforeSend: function(xhr) {
                    $('#building, #room').prop('disabled', 'disabled');
                    $('label[for=room] .loading-cog').removeClass('d-n').addClass('d-ib');
                    $('.facilities-container small').remove();
                    $('.facilities-container .custom-checkbox').remove();
                    $('.facilities-container').append(`<small>Select a room to view its facilities</small>`);
                },
                success: async function(rooms) {
                    $('#building').prop('disabled', false);
                    $('#room').empty().append(`<option value="-1" selected>Select a room...</option>`);
                    await rooms.forEach(room => {
                        $('#room').append(`<option value="${room.id}">${room.name}</option>`);
                    });
                    $('#room').prop('disabled', false);
                    $('label[for=room] .loading-cog').removeClass('d-ib').addClass('d-n');
                }
            });
        } else {
            $('#room').prop('disabled', 'disabled').empty();
            $('.facilities-container .custom-checkbox').remove();
            $('.facilities-container small').remove();
            $('.facilities-container').append(`<small>Select a room to view its facilities</small>`);
        }
    });
    $('#room').change(function(e) {
        var r_id = $('#room').val();
        var data = {
            _token: $('meta[name=csrf-token]').attr('content'),
            r_id: r_id,
        };
        console.log(data);
        if(r_id == -1) {
            $('.facilities-container .custom-checkbox').remove();
            $('.facilities-container small').remove();
            $('.facilities-container').append(`<small>Select a room to view its facilities</small>`);
        } else {
            $.ajax({
                url: '/api/roomDetail',
                method: 'post',
                data: $.param(data),
                beforeSend: function(xhr) {
                    $('#building, #room').prop('disabled', 'disabled');
                    $('label[for=facilities] .loading-cog').removeClass('d-n').addClass('d-ib');
                    $('.facilities-container .custom-checkbox').remove();
                    $('.facilities-container small').remove();
                    $('.facilities-container').append(`<small>Select a room to view its facilities</small>`);
                },
                success: function(room) {
                    $('#building, #room').prop('disabled', false);
                    $('.facilities-container small').remove();
                    room.facilities.forEach(facility => {
                        $('.facilities-container').append(`<div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="facility[${facility.id}]" id="facility-${facility.id}">
                            <label class="custom-control-label" for="facility-${facility.id}">${facility.name}</label>
                        </div>`);
                    });
                    $('label[for=facilities] .loading-cog').removeClass('d-ib').addClass('d-n');
                }
            });
            $.ajax({
                url: '/api/roomBookings',
                method: 'post',
                data: $.param(data),
                beforeSend: function(xhr) {
                    $('#calendar-overlay').removeClass('d-n').addClass('d-f');
                    $('#full-calendar').fullCalendar('removeEvents');
                },
                success: function(bookings) {
                    $('#calendar-overlay').removeClass('d-f').addClass('d-n');
                    bookings.forEach(booking => {
                        $('#full-calendar').fullCalendar('renderEvent', {
                            title: booking.details.purpose,
                            start: booking.details.start_datetime,
                            end: booking.details.end_datetime,
                            description: `${booking.details.start_datetime.slice(0,-3)} - ${booking.details.end_datetime.slice(0,-3)}`
                        }, true);
                    });
                }
            });
        }
    });
    $('#startDateTime').datetimepicker('date', '{{isset($draft->start_datetime) ? $draft->start_datetime->format('d/m/Y H:i') : '' }}');
    $('#endDateTime').datetimepicker('date', '{{isset($draft->end_datetime) ? $draft->end_datetime->format('d/m/Y H:i') : '' }}');
</script>
@endsection
