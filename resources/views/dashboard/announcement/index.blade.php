@extends('layouts.dashboard.app')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ __('Announcements') }}</li>
@endsection

@section('content')
<div id='mainContent'>
    @if(Auth::user()->is_admin)
        <div class="row gap-20 pos-r">
            <div class="col-12">
                <div>
                    <div class="layers">
                        <div class="layer w-100 pX-20 pB-10">
                            <a class="btn btn-secondary c-white" href="{{ route('announcements.create')}}" role="button"><i class="ti-marker-alt"></i> Write an announcement</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gap-20 pos-r">
            <div class="col-12">
                <div class="bd bgc-white">
                    <div class="layers">
                        <div class="layer bgc-light-blue-500 c-white w-100 p-20 bdB">
                            <h5 class="lh-1 font-weight-bold m-0">DRAFTS</h5>
                        </div>
                        <div class="layer w-100 p-20 bdB">
                            @if($drafts->isEmpty())
                                <p class="m-0">{{__('No announcement drafts')}}</p>
                            @else
                                <div class="list-group">
                                    @foreach ($drafts as $draft)
                                    <a href="{{ route('announcements.show', ['id' => $draft->id])}}" class="list-group-item list-group-item-action d-f ai-c">
                                        <span class="fxg-1">{{ $draft->title }}</span>
                                        <span class="text-muted">Last edited on: {{ $draft->updated_at->format('d-M-Y h:i') }}</span>
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
        <div class="row gap-20 pos-r">
            <div class="col-12">
                <div class="bd bgc-white">
                    <div class="layers">
                        <div class="layer w-100 p-20 bdB">
                            <h5 class="lh-1 font-weight-bold m-0 c-light-blue-500">ANNOUNCEMENTS</h5>
                        </div>
                        <div class="layer w-100 p-20 bdB">
                            @if($announcements->isEmpty())
                                <p class="m-0">{{__('There are no announcements')}}</p>
                            @else
                                @foreach ($announcements as $a)
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title font-weight-bold m-0">{{ $a->title }}</h6>
                                            <small class="card-subtitle text-muted mB-10">Posted at {{ $a->posted_at->format('d-M-Y H:i') }}</small>
                                            <p class="card-text mT-10">{{ str_limit($a->content, $limit = 100, $end = '...') }}</p>
                                            <a href="{{ route('announcements.show', ['id' => $a->id])}}">See more &rarr;</a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

</div>
@endsection
