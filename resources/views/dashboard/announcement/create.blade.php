@extends('layouts.dashboard.app')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item"><a href="{{ route('announcements.index') }}">{{__('Announcements')}}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ __('New') }}</li>
@endsection

@section('content')
<div id='mainContent'>
    <div class="row gap-20 pos-r">
        <div class="col-12">
            <div class="bd bgc-white h-100">
                <div class="layers">
                    <div class="layer w-100 pX-20 pT-20">
                        <h6 class="lh-1 font-weight-bold">{{__('MAKE AN ANNOUNCEMENT')}}</h6>
                    </div>
                    <div class="layer w-100 p-20">
                        <form id="bookingForm" action="{{ route('announcements.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="title">{{__('Title')}} <span class="c-red-500">*</span></label>
                                <input type="text" class="form-control" name="title" id="title" placeholder="" maxlength="50" required>
                            </div>
                            <div class="form-group">
                                <label for="expiry">Expiry</label>
                                <select class="form-control" name="expiry" id="expiry">
                                    <option value="0" selected>1 year</option>
                                    <option value="1">1 month</option>
                                    <option value="2">1 week</option>
                                    <option value="3">1 day</option>
                                    <option value="4">1 hour</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="content">{{__('Contents')}}</label>
                                <textarea class="form-control" name="content" id="content" rows="10"></textarea>
                            </div>
                            <div class="peers">
                                <div class="peer peer-greed">
                                    <button class="btn btn-dark" type="submit">
                                        <i class="ti-save fsz-xs mR-5"></i>
                                        {{__('Save draft')}}
                                    </button>
                                    <button class="btn btn-link c-red-500" type="button" onclick="window.history.go(-1)">
                                        {{__('Cancel')}}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
