@extends('layouts.dashboard.app')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('rooms.index') }}">Rooms</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $room->name }}</li>
@endsection

@section('content')
<div id='mainContent'>
    <div class="row gap-20 pos-r">
        <div class="col-md-4">
            <div class="bd bgc-white">
                <div class="layers">
                    <div class="layer bgc-light-blue-500 c-white w-100 p-20">
                        <div class="peer peer-greed">
                            <h5 class="lh-1 mB-0">{{__('Room')}}: {{ $room->name }}</h5>
                        </div>
                    </div>
                    @unless (Auth::user()->is_authority)
                    <div class="layer w-100 p-20">
                        <form action="{{ route('drafts.create.filled') }}" method="POST">
                            @csrf
                            <input type="hidden" name="r_id" value="{{ $room->id }}">
                            <button class="btn btn-dark" type="submit">
                                <i class="ti-bookmark fsz-xs"></i>
                                {{__('BOOK')}}
                            </button>
                        </form>
                    </div>
                    @endif
                    <div class="layer w-100">
                        <table class="table-borderless">
                            <tbody>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('Building')}}</th>
                                    <td class="pX-20 pY-10 va-t" >{{ $room->building->name }}</td>
                                </tr>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('Floor')}}</th>
                                    <td class="pX-20 pY-10 va-t" >{{ $room->floor }}</td>
                                </tr>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('Type')}}</th>
                                    <td class="pX-20 pY-10 va-t" >
                                        @forelse ( $room->types as $type )
                                            {{ $loop->first ? '' : ', '}}
                                            {{ ucwords(__($type->type)) }}
                                        @empty
                                            -
                                        @endforelse
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('Facilities')}}</th>
                                    <td class="pX-20 pY-10 va-t">
                                        @forelse ( $room->facilities as $facility )
                                            {{ $loop->first ? '' : ', '}}
                                            {{ ucwords(__($facility->name)) }}
                                        @empty
                                            -
                                        @endforelse
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div id='full-calendar'></div>
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
            description: `${booking.details.start_datetime.slice(0,-3)} - ${booking.details.end_datetime.slice(0,-3)}`
        }, true);
    });
})();

</script>
@endsection
