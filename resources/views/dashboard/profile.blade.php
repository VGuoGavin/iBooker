@extends('layouts.dashboard.app')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
<li class="breadcrumb-item active" aria-current="page">Account settings</li>
@endsection

@section('content')
<div id='mainContent'>
    <div class="row gap-20 pos-r">
        <div class="col-12">
            <div class="bd bgc-white">
                <div class="layers">
                    <div class="layer w-100 p-20 bdB">
                        <div class="row">
                            <div class="col-md-4">
                                <h5>Profile</h5>
                                <small>Your email address is your identity on RoomBooker and is used to log in</small>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="email" class="font-weight-bold">Email Address</label>
                                    <input type="text" name="email" id="email" class="form-control" placeholder="" value="{{ $user->email }}">
                                </div>
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="" value="{{ $user->name }}">
                                </div>
                                <button type="submit" class="btn btn-light border-secondary" disabled>Update</button>
                            </div>
                        </div>
                    </div>
                    <div class="layer w-100 p-20 bdB">
                        <div class="row">
                            <div class="col-md-4">
                                <h5>Other information</h5>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="phone" class="font-weight-bold">Phone number</label>
                                    <input type="tel" name="phone" id="phone" class="form-control" placeholder="" value="{{ $user->phone }}">
                                </div>
                                <div class="form-group">
                                    <label for="nik" class="font-weight-bold">NIM/NIK</label>
                                    <input type="tel" name="nik" id="nik" class="form-control" placeholder="" value="{{ $user->nik }}">
                                </div>
                                <button type="submit" class="btn btn-light border-secondary" disabled>Save</button>
                            </div>
                        </div>
                    </div>
                    <div class="layer w-100 p-20 bdB">
                        <div class="row">
                            <div class="col-md-4">
                                <h5>Password</h5>
                                <small>Keep your account secure</small>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="oldpass" class="font-weight-bold">Current Password</label>
                                    <input type="password" name="oldpass" id="oldpass" class="form-control">
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="newpass" class="font-weight-bold">New Password</label>
                                    <input type="password" name="newpass" id="newpass" class="form-control" aria-describedby="newpassHelpId">
                                    <small id="newpassHelpId">Password must be 8 or more characters</small>
                                </div>
                                <div class="form-group">
                                    <label for="confirmpass" class="font-weight-bold">Confirm New Password</label>
                                    <input type="password" name="confirmpass" id="confirmpass" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-light border-secondary" disabled>Update password</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
