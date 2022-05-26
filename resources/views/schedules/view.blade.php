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

    <a href='{{ url("schedules") }}' class="btn btn-primary"><< Back to Schedule Management page</a>
    
    <div class="row">
        <main class="py-4">

            <h3><i class="fa fa-info-circle"></i> Wpc2021 OCBS Schedule</h3>

            <h5>May 2, 2022</h5>

            <div class="card card-info">

                <div class="card-header">
                    <h3 class="card-title"><i class="fa fa-cog"></i> Schedule Management</h3>
                    <a class="btn btn-secondary float-right" href="{{ removeParam(request()->fullUrlWithQuery(['download' => '1']), 'downloadcurrent') }}">Download Excel</a>
                </div>

                <div class="card-body">

                    <form class="form-horizontal">
                        <div class="form-group row">
                        <div class="col-md-3">
                            <input type="text" class="filter-group form-control" placeholder="Group Name / Code">
                        </div>

                        <div class="col-md-3">
                            <input type="text" class="filter-group form-control" placeholder="Province">
                        </div>

                        <div class="col-md-2">
                            <select id="selectType" name="selectType" class="form-control">
                                <option selected value="">SELECT ALL TYPE</option>
                                <option value="1">ARENA</option>
                                <option value="2">OCBS-LOTTO</option>
                                <option value="3">OCBS-OTB</option>
                                <option value="4">OCBS-RESTOBAR</option>
                                <option value="5">OCBS-STORE</option>
                                <option value="6">OCBS-MALL</option>
                                <option value="7">OCBS</option>
                                <option value="8">OCBS-EGAMES</option>
                                <option value="9">OCBS-CASINO</option>
                            </select>
                        </div>

                        <!-- <div class="col-md-2">
                            <select id="siteID" name="siteID" class="form-control">
                                <option selected disabled value="">SELECT SITE</option>
                                <option value="wpc2040">WPC2040</option>
                                <option value="wpc2040aa">WPC2040AA</option>
                            </select>
                        </div> -->

                        <div class="col">
                            <button type="button" class="btn btn-success"><i class="fas fa-search"></i> Submit</button>
                        </div>
                        </div>
                    </form>

                    @include('schedules.tables.fullview')

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