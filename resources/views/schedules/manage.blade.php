@extends('layouts.app')

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <main class="py-4">
                <h3><i class="fa fa-info-circle"></i> Wpc2021 OCBS Schedule</h3>
                <h5>May 2, 2022</h5>

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


                    <form class="form-horizontal" method="POST" action='{{ url("/schedules/manage/$scheduleId") }}'>
                        @csrf
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="searchGroup" class="col-sm-2 col-form-label">Group Name</label>
                                <div class="col-sm-8">
                                    <select class="selectpicker form-control" data-live-search="true" name="group_id">
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
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-primary float-right"><i class="fas fa-plus"></i> Add Group</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                </div>

                <div class="card card-info">

                    <div class="card-header">
                        <h3 class="card-title"><i class="fa fa-list"></i> Scheduled Groups</h3>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
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

                                    <div class="col-md-2">
                                        <select id="siteID" name="siteID" class="form-control">
                                            <option selected disabled value="">SELECT SITE</option>
                                            <option value="wpc2040">WPC2040</option>
                                            <option value="wpc2040aa">WPC2040AA</option>
                                        </select>
                                    </div>

                                    <div class="col">
                                        <button type="button" class="btn btn-success"><i class="fas fa-search"></i> Submit</button>
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
                                    @endphp
                                    @foreach($groupsForDisplay as $group)
                                        <tr>
                                            <td>{{ $groupCount++ }}</td>
                                            <td>
                                                {{ $group->group_name }}
                                                @if($group->address)
                                                    ({{ $group->address }})
                                                @endif
                                            </td>
                                            <td>--</td>
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
                                            <td>--</td>
                                            <td class="display-center">
                                                <a href='{{ url("/schedules/$scheduleId/groups/$group->id") }}' class="btn btn-primary"><i class="fas fa-eye"></i> Manage Group</a>
                                                <form action='{{ url("/schedules/$scheduleId/remove/$group->id") }}' method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger"><i class="fas fa-times"></i> Remove</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
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
