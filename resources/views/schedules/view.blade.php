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

            <h4><i class="fa fa-info-circle"></i> {{ $scheduleInfo->name }}</h4>
            <h5>{{ date('l, M d Y', strtotime($scheduleInfo->date_time)) }}</h5>
            
            <div class="card card-info">

                <div class="card-header">
                    <h3 class="card-title"><i class="fa fa-cog"></i> Schedule</h3>
                    <a class="btn btn-success float-right" href="{{ removeParam(request()->fullUrlWithQuery(['download' => '1']), 'downloadcurrent') }}">Download Excel</a>
                </div>

                <div class="card-body">

                    <form class="form-horizontal" method="get">
                        <div class="form-group row">
                        <div class="col-md-3">
                            <input type="text" class="filter-group form-control" name="groupCode" placeholder="Group Code">
                        </div>

                        <div class="col-md-2">
                            <select id="selectSite" name="selectSite" class="form-control">
                                <option selected value="">SELECT ALL SITE</option>
                                <option value="wpc2040">WPC2040</option>
                                <option value="wpc2040aa">WPC2040AA</option>
                            </select>
                        </div>

                        <div class="col-md-3">
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

                        <div class="col">
                            <button type="submit" class="btn btn-success"><i class="fas fa-search"></i> Submit</button>
                            <a href="{{ url('/schedules/view') }}/{{ $scheduleId }}" class="btn btn-danger">Reset</a>
                        </div>
                        </div>
                    </form>

                    @include('schedules.tables.fullview')
                    
                    <div class="col">
                        <div class="float-right">
                            {{ $scheduledGroups->appends(['selectSite' => $selectSite, 'selectType' => $selectType])->links('pagination::bootstrap-4') }}
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
<style>
    .multiselect-native-select .btn-group {
        width: 100%;
    }
</style>
@endsection
@section('script')
<script src="{{ asset('js/bootstrap-multiselect.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('#selectType').multiselect();
});
</script>
@endsection