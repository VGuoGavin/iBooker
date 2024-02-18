@extends('layouts.dashboard.app')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item"><a href="{{ route('announcements.index') }}">{{__('Announcements')}}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $announcement->posted ? '' : __('(Draft) ')}}{{ $announcement->title }}</li>
@endsection

@section('content')
<div id='mainContent'>
    <div class="row gap-20 pos-r">
        <div class="col-12">
            <div class="bd bgc-white h-100">
                <div class="layers">
                    <div class="layer peers w-100 p-20">
                        <div class="peer">
                            <h5 class="mB-0 font-weight-bold">{{ $announcement->title }} {{ $announcement->posted ? '' : __('(Draft)')}}</h5>
                            @unless ($announcement->posted)
                            <small>Last edited on: {{ $announcement->updated_at->format('d-M-y H:i') }}</small>
                            @else
                            <small>Posted on: {{ $announcement->posted_at->format('d-M-y H:i') }}</small>
                            @endunless
                        </div>
                    </div>
                    <div class="layer w-100 pY-10 pX-20">
                        <p>
                            {{ $announcement->content }}
                        </p>
                        @unless ($announcement->posted)
                            <small>Announcement will <strong>expire after {{ $announcement->expiry_string }}</strong> being posted</small>
                        @endunless
                    </div>
                    @if (Auth::user()->is_admin && $announcement->announcer_id = Auth::user()->id)
                        <div class="layer peers w-100 {{ $announcement->posted ? '' : 'pT-20'}} pB-20">
                            <div class="peer peer-greed {{ $announcement->posted ? 'ta-r pR-20' : ''}}">
                                @unless ($announcement->posted)
                                <a name="edit" id="edit" class="btn btn-link" href="{{ route('announcements.edit', ['id' => $announcement->id])}}" role="button" style="margin-left: 8px"><i class="ti-pencil"></i> Edit</a> |
                                @endunless
                                <button type="button" id="_delete" data-toggle="modal" data-target="#deleteModal" class="btn btn-link c-grey-500 {{ $announcement->posted ? 'p-0' : ''}}">Delete</button>
                            </div>
                            @unless ($announcement->posted)
                            <div class="peer">
                                <button type="button" class="btn btn-primary mR-20" data-toggle="modal" data-target="#postModal"><i class="ti-announcement"></i> Post announcement</button>
                            </div>
                            @endunless
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if (Auth::user()->is_admin && $announcement->announcer_id = Auth::user()->id)
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalTitle" aria-hidden="true">
    <form action="{{route('announcements.delete', ['id' => $announcement->id])}}" method="post">
        @method('DELETE')
        @csrf
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="deleteModalTitle"><strong>DELETE CONFIRMATION</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <div class="modal-body">
                <div class="layers">
                    <div class="layer w-100">
                        Once deleted, the announcement will be permanently <strong class="text-danger">lost</strong>.
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
@section('custom-script')
<script>
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
@endif

@if (!$announcement->posted && Auth::user()->is_admin && $announcement->announcer_id = Auth::user()->id)
    <div class="modal fade" id="postModal" tabindex="-1" role="dialog" aria-labelledby="postModalTitle" aria-hidden="true">
    <form action="{{route('announcements.post', ['id' => $announcement->id])}}" method="post">
        @method('PUT')
        @csrf
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="postModalTitle">Post confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <div class="modal-body">
                <div class="layers">
                    <div class="layer w-100">
                        Are you sure you want to post this announcement?
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="post">Post</button>
            </div>
            </div>
        </div>
    </form>
</div>
@endif

@endsection
