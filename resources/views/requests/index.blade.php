@extends('layouts.app')

@section('content')
@section('title','Request')
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
                    <h3 class="card-title"><i class="fa fa-info-circle"></i> Requests Page</h3>
                    <a class="btn btn-success float-right" href="{{ removeParam(request()->fullUrlWithQuery(['download' => '1']), 'downloadcurrent') }}">Download Excel</a>
                </div>
                 <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" method="get">
                        <div class="form-group row">

                            <div class="col-md-3">
                                <input type="text" class="form-control" name="keyword" id="keyword" placeholder="keyword" value="">
                            </div>

                            <div class="col-md-3">
                                <select class="form-control" name="status" id="status">
                                    <option value="" selected disabled>Select All Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>

                            <div class="col">
                                <button type="submit" class="btn btn-success"><i class="fas fa-search"></i> Submit</button>
                                <a href="{{ url('/requests') }}" class="btn btn-danger">Reset</a>
                            </div>
                        </div>
                    </form>
                    
                    @include('requests.tables.requestsTable')
                                        
                    <div class="col">
                        <div class="float-right">
                            {{ $requests->appends(['keyword' => $keyword, 'status' => $status])->links('pagination::bootstrap-4') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
