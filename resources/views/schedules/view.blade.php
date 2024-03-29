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
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
    @if($scheduleInfo->status == 'active')
    <a href='{{ url("schedules") }}' class="btn btn-primary"><< Back to Schedule Management page</a>
    @else
    <a href='{{ url("schedules-past") }}' class="btn btn-primary"><< Back to Past Schedules page</a>
    @endif
    <div class="row">
        <main class="py-4">
            
            <div class="card card-info">

                <div class="card-header">
                    <h3 class="card-title"><i class="fa fa-cog"></i> Schedule</h3>
                    <a class="btn btn-success float-right" href="{{ removeParam(request()->fullUrlWithQuery(['download' => '1']), 'downloadcurrent') }}">Download Excel</a>
                </div>

                <div class="card-body">

                    <form class="form-horizontal" method="get">
                        <div class="form-group row" style="justify-content: center;">
                            <div class="col-md-3">
                                <input type="text" class="filter-group form-control" name="groupCode" placeholder="Group Code/Group Name/Address">
                            </div>

                            <div class="col-md-3">
                                <input type="text" class="filter-group form-control" name="ogName" placeholder="Operator/Guarantor">
                            </div>

                            <div class="col-md-2">
                                <input type="text" class="filter-group form-control" name="operation_time" id="operation_time" placeholder="Operation Time">
                            </div>

                            <div class="col-md-2">
                                <select id="selectSite" name="selectSite" class="form-control">
                                    <option selected value="">SELECT ALL SITE</option>
                                    <option value="wpc2040">WPC2040</option>
                                    <option value="wpc2040aa">WPC2040AA</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row" style="justify-content: center;">
                            <div class="col-md-4">
                                <select id="selectType" name="selectType[]" class="form-control"  multiple="multiple">
                                    <option selected value="">SELECT ALL TYPE</option>
                                    <option value="ARENA">ARENA</option>
                                    <option value="OCBS-LOTTO">OCBS-LOTTO</option>
                                    <option value="OCBS-OTB">OCBS-OTB</option>
                                    <option value="OCBS-RESTOBAR">OCBS-RESTOBAR</option>
                                    <option value="OCBS-STORE">OCBS-STORE</option>
                                    <option value="OCBS-MALL">OCBS-MALL</option>
                                    <option value="OCBS">OCBS</option>
                                    <option value="OCBS-EGAMES">OCBS-EGAMES</option>
                                    <option value="OCBS-CASINO">OCBS-CASINO</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <select id="pagination" name="pagination" class="form-select">
                                    <option selected value="">Show Entries</option>
                                    <option value="100">100</option>
                                    <option value="all">Show All</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <button type="submit" class="btn btn-success"><i class="fas fa-search"></i> Submit</button>
                                <a href="{{ url('/schedules/view') }}/{{ $scheduleId }}" class="btn btn-danger">Reset</a>
                            </div>
                        </div>
                    </form>

                    @include('schedules.tables.fullview')
                    
                    <div class="col">
                        <div class="float-right">
                            {{ $scheduledGroups->appends(Request::except('page'))->links('pagination::bootstrap-4') }}
                        </div>
                    </div>

                </div>

            </div>

        </main>
    </div>
    <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
@section('style')
<link href="{{ asset('css/bootstrap-multiselect.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/timepicker.min.css') }}" rel="stylesheet">
<style>
    .multiselect-native-select .btn-group {
        width: 100%;
    }
</style>
@endsection
@section('script')
<script src="{{ asset('js/timepicker.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-multiselect.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('#selectType').multiselect();
    
    $('#operation_time').timepicker({
        timeFormat: 'hh:mm p',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });
});
</script>
@endsection