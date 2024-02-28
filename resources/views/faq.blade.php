<!DOCTYPE html>
<html>
<!-- ### $Topbar ### -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>iBooker</title>
    <link href="images/iBooker.png" rel="icon" type="image/x-ico">
    <link href="{{ mix('css/dashboard.css') }}" rel="stylesheet" type="text/css">
    @yield('custom-css')
</head>
</html>

@extends('layouts.front.app')

@section('content')
<section class="content">
<div class="container">
    <h1>ABOUT</h1>
    <p>Insert content here</p>
</div>
</section>
@endsection
