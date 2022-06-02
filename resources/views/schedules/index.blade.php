@extends('layouts.app')

@section('content')
@php
if (! function_exists('removeParam')) {
    function removeParam($url, $param) {
        $url = preg_replace('/(&|\?)'.preg_quote($param).'=[^&]*$/', '', $url);
        $url = preg_replace('/(&|\?)'.preg_quote($param).'=[^&]*&/', '$1', $url);
        return $url;
    }
}
@endphp
<div class="container-fluid">
    
    <!-- <a href='{{ url("schedules") }}' class="btn btn-primary"><< Back to Schedule Management page</a> -->

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Schedules
                    <a class="btn btn-success float-right" href="{{ removeParam(request()->fullUrlWithQuery(['download' => '1']), 'downloadcurrent') }}">Download Excel</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <router-view />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
