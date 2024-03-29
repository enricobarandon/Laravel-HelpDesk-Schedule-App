@extends('layouts.app')

@php
if (! function_exists('removeParam')) {
    function removeParam($url, $param) {
        $url = preg_replace('/(&|\?)'.preg_quote($param).'=[^&]*$/', '', $url);
        $url = preg_replace('/(&|\?)'.preg_quote($param).'=[^&]*&/', '$1', $url);
        return $url;
    }
}
@endphp
@section('content')
@section('title','Activity-Logs')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fa fa-info-circle"></i> Activity Logs</h3>
                    <a class="btn btn-success float-right" href="{{ removeParam(request()->fullUrlWithQuery(['download' => '1']), 'downloadcurrent') }}">Download Excel</a>
                </div>

                <div class="card-body">
                    <form class="form-horizontal" method="get">
                        <div class="form-group row">

                            <div class="col-md-4">
                                <select class="form-control" name="action" id="action">
                                    <option value="" selected disabled>-Filter Actions-</option>
                                    @foreach($actions as $key => $value)
                                    <option value="{{ $key }}" {{ $key == $action ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="col-md-3">
                                <input type="text" class="form-control" name="keyword" id="keyword" placeholder="keyword" value="{{ $keyword }}">
                            </div>

                            <div class="col-md-3">
                                <input type="text" class="form-control" name="datepicker" id="datepicker" placeholder="Select Date" value="">
                            </div>

                            <div class="col">
                                <button type="submit" class="btn btn-success"><i class="fas fa-search"></i> Submit</button>
                                <a href="{{ url('/logs') }}" class="btn btn-danger">Reset</a>
                            </div>
                        </div>
                    </form>

                    @include('logs.tables.logs-table')

                    <div class="col">
                        <div class="float-right">
                            {{ $activityLogs->appends(['keyword' => $keyword, 'datepicker' => $datepicker])->links('pagination::bootstrap-4') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
<link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
@endsection

@section('script')
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
<script>
    $("document").ready(function(){
        $('#datepicker').datepicker({
            format: 'yyyy-mm-dd',
        });
    });
</script>
@endsection
