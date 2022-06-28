@extends('layouts.app')

@section('content')
@section('title','Schedule')
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
                <h3 class="card-title"><i class="fa fa-info-circle"></i> Past Schedules</h3>
                    <a class="btn btn-success float-right" href="{{ removeParam(request()->fullUrlWithQuery(['download' => '1']), 'downloadcurrent') }}">Download Excel</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @elseif(session('error'))
                        <div class="alert alert-danger" role="alert">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {{ session('error') }}
                        </div>
                    @endif

                    <table class="table table-bordered table-striped global-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Schedule Name</th>
                                <th>Schedule Date</th>
                                <th>Created At</th>
                                <th>Date Finished</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pastSchedules as $schedule)
                                <tr>
                                    <td>{{ $schedule->id }}</td>
                                    <td>{{ $schedule->name }}</td>
                                    <td>{{ date("M d, Y",strtotime($schedule->date_time)) }}</td>
                                    <td>{{ date("M d, Y h:i:s a",strtotime($schedule->created_at)) }}</td>
                                    <td>{{ date("M d, Y h:i:s a",strtotime($schedule->updated_at)) }}</td>
                                    <td class="display-center">
                                        <a class="btn btn-info btn-view" href='/schedules/view/{{ $schedule->id }}'><i class="fas fa-eye"></i> View Schedule</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
