@extends('layouts.dashboard.app')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">{{__('Bookings')}}</a></li>
<li class="breadcrumb-item active" aria-current="page">({{__('Draft')}}) {{ $draft->id }}</li>
@endsection

@section('content')
<div id='mainContent'>
    <div class="row gap-20 pos-r">
        <div class="col-md-6">
            <div class="bd bgc-white h-100">
                <div class="layers">
                    <div class="layer peers bgc-light-blue-500 c-white w-100 p-20">
                        <div class="peer">
                            <h5 class="mB-0">{{__('Booking Draft')}}: {{ $draft->id }}</h5>
                        </div>
                        <div class="peer">
                            @if ($draft->is_complete)
                                <span class="badge badge-pill badge-light text-success mL-10">Complete</span>
                            @else
                                <span class="badge badge-pill badge-light text-danger mL-10">Incomplete</span>
                            @endif
                        </div>
                    </div>
                    <div class="layer w-100 pY-10">
                        <table class="table-borderless">
                            <tbody>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('Building')}}</th>
                                    <td class="pX-20 pY-10 va-t" >
                                        @isset($draft->room)
                                            {{$draft->room->building->name}}
                                        @else
                                            <strong class="text-danger">no room picked</strong>
                                        @endisset
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('Room')}}</th>
                                    <td class="pX-20 pY-10 va-t" >
                                        @isset($draft->room)
                                            {{$draft->room->name}}
                                        @else
                                            <strong class="text-danger">no room picked</strong>
                                        @endisset
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('Start use')}}</th>
                                    <td class="pX-20 pY-10 va-t" >
                                        @isset($draft->start_datetime)
                                            {{$draft->start_datetime->format('d-M-Y H:i')}}
                                        @else
                                            <strong class="text-danger">not set</strong>
                                        @endisset
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('End use')}}</th>
                                    <td class="pX-20 pY-10 va-t" >
                                        @isset($draft->end_datetime)
                                            {{$draft->end_datetime->format('d-M-Y H:i')}}
                                        @else
                                            <strong class="text-danger">not set</strong>
                                        @endisset
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('Purpose')}}</th>
                                    <td class="pX-20 pY-10 va-t" >
                                        @isset($draft->purpose)
                                            {{$draft->purpose}}
                                        @else
                                            <strong class="text-danger">not set</strong>
                                        @endisset
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('Facilities required')}}</th>
                                    <td class="pX-20 pY-10 va-t">
                                        @forelse ( $draft->facilities as $facility )
                                            {{ $loop->first ? '' : ', '}}
                                            {{ ucwords(__($facility->name)) }}
                                        @empty
                                        -
                                        @endforelse
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pX-20 pY-10 va-t" scope="row">{{__('Other comments')}}</th>
                                    <td class="pX-20 pY-10 va-t" >{{ isset($draft->comments) ? $draft->comments : '-'}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="layer w-100 pB-20">
                    <a name="edit" id="edit" class="btn btn-link" href="{{ route('drafts.edit', ['id' => $draft->trimmed_id])}}" role="button" style="margin-left: 8px"><i class="ti-pencil"></i> Edit</a>|<button type="button" id="_delete" data-toggle="modal" data-target="#deleteModal" class="btn btn-link c-grey-500">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 order-md-last order-first">
            <div class="bd bgc-white h-100">
                <div class="layers">
                    <div class="layer bdB peers w-100 p-20">
                        <div class="peer peer-greed">
                            <h5 class="mB-0"><strong>Completion: {{ $draft->completion['percent'] }}%</strong></h5>
                        </div>
                        <div class="peer">
                            @if ($draft->is_complete)
                                <button type="button" class="btn btn-primary mL-20" data-toggle="modal" data-target="#commitModal"><i class="ti-alert"></i> Sign form</button>
                            @else
                                <button type="button" class="btn btn-primary mL-20" disabled title="Complete the followng"><i class="ti-alert"></i> Sign form</button>
                            @endif
                        </div>
                    </div>
                    <div class="layer w-100 p-20">
                        <ul class="list-group">
                            @foreach ($draft->completion['messages'] as $message)
                                <li class="list-group-item">
                                    @switch($message['status'])
                                        @case('complete')
                                            <i class="fas fa-check-circle mR-10 text-success"></i>
                                            @break
                                        @case('incomplete')
                                            <i class="fas fa-times-circle mR-10 text-danger"></i>
                                            @break
                                        @case('warning')
                                            <i class="fas fa-exclamation-circle mR-10 c-amber-700"></i>
                                            @break
                                        @default

                                    @endswitch
                                    {{ $message['message'] }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalTitle" aria-hidden="true">
    <form action="{{route('drafts.delete', ['id' => $draft->trimmed_id])}}" method="post">
        @method('DELETE')
        @csrf
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="deleteModalTitle"><strong>No turning back!</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <div class="modal-body">
                <div class="layers">
                    <div class="layer w-100">
                        Once deleted, all data related to the current draft will be permanently <strong class="text-danger">lost</strong> and <strong class="text-danger">cannot be recovered</strong>.
                    </div>
                    <div class="layer w-100 pT-20">
                        <div class="form-group">
                            <label for="digits">Enter <strong><span id="randoms"></span></strong> to delete</label>
                            <input type="text" name="digits" id="digits" class="form-control" placeholder="XXXXXX" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger" id="delete" disabled>Delete</button>
            </div>
            </div>
        </div>
    </form>
</div>

@if ($draft->is_complete)
<div class="modal fade" id="commitModal" tabindex="-1" role="dialog" aria-labelledby="commitModalTitle" aria-hidden="true">
    <form action="{{route('drafts.commit', ['id' => $draft->trimmed_id])}}" method="post">
        @method('PUT')
        @csrf
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
                        Once signed, all data are considered <strong class="text-danger">final</strong> and <strong class="text-danger">cannot be changed</strong>.
                    </div>
                    <div class="layer w-100 pT-20">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="agree" id="agree" required>
                            <label class="custom-control-label" for="agree">By clicking submit, I acknowledge that all submitted data are <strong class="text-danger">valid</strong> and will <strong class="text-danger">not</strong> be changed.</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="sign" disabled>Sign</button>
            </div>
            </div>
        </div>
    </form>
</div>
@endif
@endsection

@section('custom-script')
<script>
    $('#agree').change(function(e) {
        if($('#agree').is(":checked"))
            $('#sign').attr('disabled', false);
        else
            $('#sign').attr('disabled', 'disabled');
    });
    $('#_delete').click(function(e) {
        $('#randoms').text(Math.random().toString(10).replace(/0\.?/, '').slice(0,6).toUpperCase());
        $('#delete').attr('disabled', 'disabled');
        $('#digits').val('');
    });
    $('#digits').keydown(function(e) {
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
            (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
        if ($(this).val().length >= 6) e.preventDefault();
    });
    $('#digits').keyup(function(e) {
        let val = $(this).val();
        let random = $('#randoms').text();
        if(val == random){
            $('#delete').attr('disabled', false);
        } else {
            $('#delete').attr('disabled', 'disabled');
        }
    });

</script>
@endsection
