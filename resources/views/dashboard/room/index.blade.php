@extends('layouts.dashboard.app')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
<li class="breadcrumb-item active" aria-current="page">Rooms</li>
@endsection

@section('content')
<div id='mainContent'>
    <div class="row gap-20 pos-r">
        {{-- <div class="col-md-4"></div> --}}
        <div class="col-md-5">
            <div class="bd bgc-white">
                <div class="layers">
                    <div class="layer w-100 pX-20 pT-20">
                        <h6 class="lh-1 font-weight-bold">BUILDINGS</h6>
                    </div>
                    <div class="layer w-100 p-20">
                        <div class="peers gap-15 ta-c fxw-w">
                            @forelse ($buildings as $building)
                                <div class="peer peer-greed">
                                    <a class="btn {{ $building == $chosen ? 'btn-dark' : 'btn-light border-secondary'}}" href="?b={{ $building->id }}" role="button">{{ $building->name }}</a>
                                </div>
                            @empty
                                <p class="mB-0">{{__('No buildings')}}</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="bd bgc-white">
                <div class="layers">
                    <div class="layer w-100 pX-20 pT-20">
                        <h6 class="lh-1 font-weight-bold">ROOMS</h6>
                    </div>
                    @if (isset($chosen))
                        <div class="layer bgc-light-blue-500 c-white w-100 p-20 mT-15">
                            <div class="peer peer-greed">
                                <h5 class="lh-1 mB-0">{{__('Building')}}: {{ $chosen->name }}</h5>
                            </div>
                        </div>
                    @endif
                    <div class="row w-100 p-20">
                        @if (isset($chosen))
                            <table id="dataTable" class="table table-striped" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Room</th>
                                        <th>Floor</th>
                                        <th>Type</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($rooms as $room)
                                        <tr>
                                            <td>{{ $room->name }}</td>
                                            <td>{{ $room->floor }}</td>
                                            <td>
                                                @forelse ( $room->types as $type )
                                                    {{ $loop->first ? '' : ', '}}
                                                    {{ ucwords($type->type) }}
                                                @empty
                                                    -
                                                @endforelse
                                            </td>
                                            <td class="text-right pT-5 pB-3"><a class="btn btn-dark" href="{{ route('rooms.show', ['id' => $room->id]) }}">Details</a></td>
                                        </tr>
                                    @empty
                                        <p class="mB-0">{{ __('No rooms in this building') }}</p>
                                    @endforelse
                                </tbody>
                            </table>
                        @else
                            <p class="mB-0">{{ __('Click on a building to view its rooms') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
