@extends('layouts.dashboard.app')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ __('Bookings') }}</li>
@endsection

@section('content')
<div id='mainContent'>
    @if(Auth::user()->is_admin)
        <div class="row gap-20 pos-r">
            <div class="col-md-6">
                <div class="bd bgc-white">
                    <div class="layers">
                        <div class="layer bgc-light-blue-500 c-white w-100 p-20 bdB">
                            <h5 class="lh-1 font-weight-bold m-0">QUEUE</h5>
                        </div>
                        <div class="layer w-100 p-20 bdB">
                            @if($queue->isEmpty())
                                <p class="m-0">{{__('No queued booking requests')}}</p>
                            @else
                                <table class="table table-striped" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Room</th>
                                            <th>Purpose</th>
                                            <th>Requested at</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($queue as $booking)
                                            <tr>
                                                <td>{{ $booking->details->room->name }}</td>
                                                <td>{{ $booking->details->purpose}}</td>
                                                <td>{{ $booking->updated_at}}</td>
                                                <td class="text-right pT-5 pB-3"><a class="btn btn-dark" href="{{ route('bookings.show', ['id' => $booking->trimmed_id]) }}">Details</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endempty
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="bd bgc-white">
                    <div class="layers">
                        <div class="layer w-100 p-20 bdB">
                            <h5 class="lh-1 font-weight-bold m-0 c-light-blue-500">COMPLETED BOOKINGS</h5>
                        </div>
                        <div class="layer w-100 p-20 bdB">
                            @if($history->isEmpty())
                                <p class="m-0">{{__('No past completed booking requests')}}</p>
                            @else
                                <table id="dataTable" class="table table-striped" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Room</th>
                                            <th>Booked on</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($history as $booking)
                                            <tr>
                                                <td>{{ $booking->details->room->name }}</td>
                                                <td>{{ $booking->details->start_datetime->format('d/m/Y')}}</td>
                                                <td>
                                                    @if($booking->is_incomplete)
                                                        <span class="badge badge-pill badge-light text-danger">INCOMPLETE</span>
                                                    @elseif($booking->is_acknowledged)
                                                        <span class="badge badge-pill badge-secondary">PENDING</span>
                                                    @elseif($booking->is_accepted)
                                                        <span class="badge badge-pill badge-success mL-10">ACCEPTED</span>
                                                    @elseif($booking->is_rejected)
                                                        <span class="badge badge-pill badge-danger">REJECTED</span>
                                                    @endif
                                                </td>
                                                <td class="text-right pT-5 pB-3"><a class="btn btn-dark" href="{{ route('bookings.show', ['id' => $booking->trimmed_id]) }}">Details</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endempty
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
    <div class="row gap-20 pos-r">
        <div class="col-12">
            <div>
                <div class="layers">
                    <div class="layer w-100 pX-20 pB-10">
                        <a class="btn btn-secondary c-white" href="{{ route('drafts.create.empty')}}" role="button"><i class="ti-bookmark"></i> Book a room</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gap-20 pos-r">
        <div class="col-md-6">
            <div class="bd bgc-white">
                <div class="layers">
                    <div class="layer w-100 p-20 bdB">
                        <h5 class="lh-1 font-weight-bold text-danger m-0">INCOMPLETE</h5>
                    </div>
                    <div class="layer w-100 p-20 bdB">
                        @if($incompletes->isEmpty())
                            <p class="m-0">{{__('No booking requests need acknowledgement')}}</p>
                        @else
                            <div class="list-group">
                                @foreach ($incompletes as $booking)
                                <a href="{{ route('bookings.show', ['id' => $booking->trimmed_id])}}" class="list-group-item list-group-item-action d-f ai-c">
                                    <span class="fxg-1">{{ $booking->details->room->name }} ({{ $booking->details->purpose}})</span>
                                    <span class="badge badge-pill badge-warning">ACKNOWLEDGEMENT</span>
                                </a>
                                @endforeach
                            </div>
                        @endempty
                    </div>
                    <div class="layer w-100 pX-20 pT-20">
                        <h6 class="lh-1 font-weight-bold">DRAFTS</h6>
                    </div>
                    <div class="layer w-100 p-20">
                        @if($drafts->isEmpty())
                            <p class="m-0">{{__('No drafts are in the making')}}</p>
                        @else
                            <div class="list-group">
                                @foreach ($drafts as $draft)
                                <a href="{{ route('drafts.show', ['id' => $draft->trimmed_id])}}" class="list-group-item list-group-item-action d-f ai-c">
                                    <span class="fxg-1">{{ $draft->id }}</span>
                                    @if($draft->is_complete)
                                    <span class="badge badge-pill badge-light border border-success text-success">COMPLETE</span>
                                    @else
                                    <span class="badge badge-pill badge-light border border-danger text-danger">INCOMPLETE</span>
                                    @endif
                                </a>
                                @endforeach
                            </div>
                        @endempty
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="bd bgc-white">
                <div class="layers">
                    <div class="layer w-100 p-20 bdB">
                        <h5 class="lh-1 font-weight-bold text-primary m-0">COMPLETE</h5>
                    </div>
                    <div class="layer w-100 p-20">
                        @if($completes->isEmpty())
                            <p class="m-0">{{__('No booking requests are complete')}}</p>
                        @else
                            <div class="list-group">
                                @foreach ($completes as $booking)
                                <a href="{{ route('bookings.show', ['id' => $booking->trimmed_id])}}" class="list-group-item list-group-item-action d-f ai-c">
                                    <span class="fxg-1">{{ $booking->details->room->name }} ({{ $booking->details->purpose}}) </span>
                                    @if($booking->is_acknowledged)
                                        <span class="badge badge-pill badge-secondary">PENDING</span>
                                    @elseif($booking->is_accepted)
                                        <span class="badge badge-pill badge-success">ACCEPTED</span>
                                    @elseif($booking->is_rejected)
                                        <span class="badge badge-pill badge-danger">REJECTED</span>
                                    @endif
                                </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
