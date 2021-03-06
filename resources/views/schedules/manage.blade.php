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
<div class="content">
    <div class="container-fluid">

            <a href='{{ url("schedules") }}' class="btn btn-primary"><< Back to Schedule Management page</a>

        <div class="row">
            <main class="py-4">
                <h3><i class="fa fa-info-circle"></i> {{ $scheduleInfo->name }}</h3>
                <h5>{{ date('M d, Y', strtotime($scheduleInfo->date_time)) }}</h5>

                <div class="card card-info">

                    <div class="card-header">
                        <h3 class="card-title"><i class="fa fa-cog"></i> Schedule Management</h3>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {{ session('success') }}
                        </div>
                    @elseif(session('error'))
                        <div class="alert alert-danger" role="alert">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- <div class="form-group">
                        <form class="form-horizontal" method="get">
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <input type="text" class="filter-group form-control" name="filter-code" id="filter-code" placeholder="Group Code">
                                </div>

                                <div class="col-md-2">
                                    <select class="form-control" name="select-province" id="select-province">
                                        <option selected disabled value="">Select Province</option>
                                        @foreach($provinces as $province)
                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                        <select id="select-type" name="select-type[]" multiple="multiple" class="form-control">
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
                                    <a href="{{ url('schedules/manage') }}/{{ $scheduleId }}" class="btn btn-danger">Reset</a>
                                </div>
                        </form>
                    </div> -->


                    <form class="form-horizontal" id="frmAddGroup" method="POST" action='{{ url("/schedules/manage/$scheduleId") }}'>
                        @csrf
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="searchGroup" class="col-sm-2 col-form-label">Group Name</label>
                                <div class="col-sm-8">
                                    <!-- <select class="selectpicker form-control" data-live-search="true" name="group_id">
                                        <option value="0" selected disabled>Select Group</option>
                                        @if(count($groupsForSelect) >= 1)
                                        <option value="all">Select All Active Groups</option>
                                        @endif

                                        @foreach($groupsForSelect as $group)
                                            <option value="{{ $group->id }}">
                                                {{ $group->name }}
                                                @if($group->address)
                                                    ({{ $group->address }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select> -->
                                    <select class="selectpicker form-control" data-live-search="true" multiple="multiple" name="group_id[]" id="select-type">
                                        <option value="0" disabled>Select Group</option>
                                        @if(count($groupsForSelect) >= 1)
                                        <option value="ALL">Select All Active Groups</option>
                                        @endif
                                        <option value="ARENA">Select All ARENA</option>
                                        <option value="OCBS-LOTTO">Select All OCBS-LOTTO</option>
                                        <option value="OCBS-OTB">Select All OCBS-OTB</option>
                                        <option value="OCBS-RESTOBAR">Select All OCBS-RESTOBAR</option>
                                        <option value="OCBS-STORE">Select All OCBS-STORE</option>
                                        <option value="OCBS-MALL">Select All OCBS-MALL</option>
                                        <option value="OCBS">Select All OCBS</option>
                                        <option value="OCBS-EGAMES">Select All OCBS-EGAMES</option>
                                        <option value="OCBS-CASINO">Select All OCBS-CASINO</option>


                                        @foreach($groupsForSelect as $group)
                                            <option value="{{ $group->id }}">
                                                {{ $group->name }}
                                                    @if($group->province)
                                                        ({{ $group->province }})
                                                    @endif
                                                    @if($group->guarantor)
                                                        ({{ $group->guarantor }})
                                                    @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-primary float-right btn-add-group"><i class="fas fa-plus"></i> Add Group</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                </div>

                <div class="card card-info">

                    <div class="card-header">
                        <h3 class="card-title"><i class="fa fa-list"></i> Scheduled Groups</h3>
                        <a class="btn btn-success float-right" href="{{ removeParam(request()->fullUrlWithQuery(['siteID' => $siteID,
                                                                                                            'selectType' => $selectType,
                                                                                                            'selectProvince' => $selectProvince,
                                                                                                            'download' => '1',]), 'downloadcurrent') }}">Download Excel</a>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <form class="form-horizontal" method="get">
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <input type="text" class="filter-group form-control" name="filterGroup" id="filterGroup" placeholder="Group Code">
                                    </div>

                                    <div class="col-md-2">
                                        <!-- <input type="text" class="filter-group form-control" name="filterProvince" id="filterProvince" placeholder="Province"> -->
                                        <select class="form-control" name="selectProvince" id="selectProvince">
                                            <option selected disabled value="">Select Province</option>
                                            @foreach($provinces as $province)
                                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <select id="selectType" name="selectType[]" multiple="multiple" class="form-control">
                                            <option selected value="">SELECT ALL TYPE</option>
                                            <option value="ARENA">ARENA</option>
                                            <option value="OCBS-LOTTO">OCBS-LOTTO</option>
                                            <option value="OCBS-OTB">OCBS-OTB</option>
                                            <option value="OCBS-RESTOBAR">OCBS-RESTOBAR</option>
                                            <option value="OCBS-STORE">OCBS-STORE</option>
                                            <option value="OCBS-MALL">OCBS-MALL</option>
                                            <option value="OCBS">OCBS</option>
                                            <option value="OCBS-EGAMES8">OCBS-EGAMES</option>
                                            <option value="OCBS-CASINO">OCBS-CASINO</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <select id="siteID" name="siteID" class="form-control">
                                            <option selected disabled value="">SELECT SITE</option>
                                            <option value="wpc2040">WPC2040</option>
                                            <option value="wpc2040aa">WPC2040AA</option>
                                        </select>
                                    </div>

                                    <div class="col">
                                        <button type="submit" class="btn btn-success"><i class="fas fa-search"></i> Submit</button>
                                        <a href="{{ url('schedules/manage') }}/{{ $scheduleId }}" class="btn btn-danger">Reset</a>
                                    </div>
                                </div>
                            </form>

                            <table class="table table-bordered global-table">

                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Group Name</th>
                                        <th>Code</th>
                                        <th>Site</th>
                                        <th>Province</th>
                                        <th>Operation Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $groupCount = 1;
                                        $siteColorArr = [
                                            'wpc2040' => 'td-blue',
                                            'wpc2040aa' => 'td-red'   
                                        ];
                                        $groupsForDisplayCount = count($groupsForDisplay);
                                    @endphp

                                    @if($groupsForDisplayCount > 0)
                                        @foreach($groupsForDisplay as $group)
                                            <tr>
                                                <td>{{ $groupCount++ }}</td>
                                                <td>
                                                    {{ $group->group_name }}
                                                    @if($group->address)
                                                        ({{ $group->address }})
                                                    @endif
                                                </td>
                                                <td>{{ $group->code }}</td>
                                                <td class="
                                                    <?php
                                                        if($group->site == 'wpc2040') {
                                                            echo 'td-blue';
                                                        } else if($group->site == 'wpc2040aa'){
                                                            echo 'td-red';
                                                        }
                                                    ?>
                                                ">
                                                    {{ strtoupper($group->site) }}
                                                </td>
                                                <td>{{ $group->province_name }}</td>
                                                <td>
                                                    @if($scheduledGroupsOperationHours[$group->id]['operation_time'])
                                                        {{ date('h:i A',strtotime($scheduledGroupsOperationHours[$group->id]['operation_time'])) }}
                                                    @else
                                                        {{ 'null' }}
                                                    @endif
                                                </td>
                                                <td class="display-center">
                                                    <a href='{{ url("/schedules/$scheduleId/groups/$group->id") }}' class="btn btn-primary"><i class="fas fa-eye"></i> Manage</a>
                                                    <form action='{{ url("/schedules/$scheduleId/remove/$group->id") }}' method="POST" id="frmRemoveGroup">
                                                        @csrf
                                                        <input type="hidden" id="hdnSchedId" value="{{ $scheduleId }}"/>
                                                        <button type="button" id="{{ $group->id }}" class="btn btn-danger btnRemoveGroup"><i class="fas fa-times"></i> Remove</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr><td colspan=7 class="text-center">No Data</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
            </main>
        </div>
    <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

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
<script src="{{ asset('js/timepicker.min.js') }}"></script>
<script src="{{ asset('js/script.js') }}"></script>
<script>
$(document).ready(function() {
    $('#selectType').multiselect();
    $('#select-type').multiselect({
         enableFiltering: true,
         maxHeight: 300,
         enableCaseInsensitiveFiltering: true      
    });
    $('.btn-add-group').on('click',function()
    {
        $(this).text('Please wait ...')
        .attr('disabled','disabled');
        $('#frmAddGroup').submit();
    });
});
</script>
@endsection
