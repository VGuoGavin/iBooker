@extends('layouts.dashboard.app')

@section('custom-css')
    <style>
        .hoverable {
            position: relative;
        }
        .hoverable::after {
            position: absolute;
            top: 0;
            left: 0;
            content: ' ';
            display: block;
            width: 100%;
            height: 100%;
            background: black;
            opacity: 0;
            transition: opacity .1s linear;
        }
        .hoverable:hover::after {
            opacity: .05;
        }
    </style>
@endsection

@section('content')
<div id="mainContent">
    <div class="row gap-20 masonry pos-r">
        <div class="masonry-sizer col-md-6"></div>
        <div class="masonry-item  w-100">
            <div class="row gap-20">
                <div class='col-md-3'>
                    <a class="hoverable layers bd bgc-red-400 c-white p-20" href="{{ route('drafts.create.empty') }}">
                        <div class="layer w-100 mB-10">
                            <h6 class="lh-1"><strong>Create new booking</strong></h6>
                        </div>
                        <div class="layer w-100">
                            <div class="peers ai-c fxw-nw">
                                <div class="peer peer-greed">
                                    <i class="ti-bookmark-alt fsz-xl"></i>
                                </div>
                                <div class="peer">
                                    <span class="d-ib lh-0 va-m fw-600 bdrs-10em p-5 bgc-white c-red-400"><i class="ti-plus fsz-xs"></i></span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class='col-md-3'>
                    <div class="layers bd bgc-white p-20">
                        <div class="layer w-100 mB-10">
                            <h6 class="lh-1">Current date & time</h6>
                        </div>
                        <div class="layer w-100">
                            <div class="peers ai-sb fxw-nw">
                                <div class="peer peer-greed">
                                    <i class="ti-alarm-clock fsz-xl"></i>
                                </div>
                                <div class="peer">
                                    <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-grey-200 c-grey-700" id="clock"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class="hoverable layers bd bgc-white p-20">
                        <div class="layer w-100 mB-10">
                            <h6 class="lh-1">Nearest upcoming event</h6>
                        </div>
                        <div class="layer w-100">
                            <div class="peers ai-sb fxw-nw">
                                <div class="peer peer-greed">
                                    <i class="ti-calendar fsz-xl"></i>
                                </div>
                                <div class="peer">
                                    <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-red-50 c-red-500">{{ isset($upcoming) ? $upcoming->details->purpose : '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class="hoverable layers bd bgc-white p-20">
                        <div class="layer w-100 mB-10">
                            <h6 class="lh-1">Pending requests</h6>
                        </div>
                        <div class="layer w-100">
                            <div class="peers ai-sb fxw-nw">
                                <div class="peer peer-greed">
                                    <i class="c-amber-700 ti-timer fsz-xl"></i>
                                </div>
                                <div class="peer">
                                    <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-amber-100 c-amber-700">{{ $pending_num }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="masonry-item col-md-6">
            <!-- #Monthly Stats ==================== -->
            <div class="bd bgc-white">
                <div class="layers">
                    <div class="layer peers w-100 p-20 ai-c bdB">
                        <h5 class="lh-1 font-weight-bold m-0 peer peer-greed">ANNOUNCEMENTS</h5>
                        <a class="peer" href="">View all</a>
                    </div>
                    <div class="layer w-100 p-20">
                        @unless ($announcements->isEmpty())
                            <div class="list-group">
                                @foreach ($announcements as $announcement)
                                    <a href="#" class="list-group-item list-group-item-action tov-e ai-c d-f">
                                        <span class="fxg-1 font-weight-bold">{{ str_limit($announcement->title, $limit = 20, $end = '...')}}</span>
                                        <span>Posted on {{ $announcement->posted_at->format('d-M-y H:i') }}</span>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="m-0">There are no announcements</p>
                        @endunless
                    </div>
                </div>
            </div>
        </div>
        <div class="masonry-item col-md-6">
            <div class="bd bgc-white">
                <div class="layers">
                    <div class="layer peers w-100 p-20 ai-c bdB">
                        <h5 class="lh-1 font-weight-bold m-0 peer peer-greed c-red-400">BOOKING REQUESTS</h5>
                        <a class="peer" href="{{ route('bookings.index') }}">View all</a>
                    </div>
                    <div class="layer w-100 p-20">
                        <p class="m-0">No booking requests are being processed. Want to <a href="{{route('drafts.create.empty')}}">create a booking</a>?</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-script')
    <script>
        setInterval(() => $('#clock').text(moment().format('DD-MMM-YYYY HH:mm:ss')), 1000);
    </script>
@endsection
