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
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('Group Management') }}
                    <a class="btn btn-success float-right" href="{{ removeParam(request()->fullUrlWithQuery(['download-'.request()->segment(3).'' => '1']), 'active') }}">Download Excel</a>
                    <a class="btn btn-primary float-right" href="{{ route('groups.create') }}"><i class="fa fa-plus"></i> Create Group</a>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @elseif(session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    <router-view />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
