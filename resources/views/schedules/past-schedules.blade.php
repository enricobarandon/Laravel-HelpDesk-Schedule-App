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
                <h3 class="card-title"><i class="fa fa-info-circle"></i> Past Schedules</h3>
                    <a class="btn btn-success float-right" href="{{ removeParam(request()->fullUrlWithQuery(['download-finish' => '1']), 'downloadcurrent') }}">Download Excel</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table table-bordered table-striped global-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Schedule Name</th>
                                <th>Schedule Date</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $scheduleCount = 1;
                            @endphp
                            @foreach($pastSchedules as $schedule)
                                <tr>
                                    <td>{{ $scheduleCount++ }}</td>
                                    <td>{{ $schedule->name }}</td>
                                    <td>{{ date("M d, Y",strtotime($schedule->date_time)) }}</td>
                                    <td>{{ date("M d, Y h:i:s a",strtotime($schedule->created_at)) }}</td>
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
