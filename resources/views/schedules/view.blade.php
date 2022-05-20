@extends('layouts.app')

@section('content')
<!-- Main content -->
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

                    @php
                        $groupCount = 1;
                    @endphp

                    @foreach($groups as $group)

                        @php
                            $siteClass = '';
                            if ($group->site == 'wpc2040'){
                                $siteClass = 'td-blue';
                            } else if ($group->site == 'wpc2040aa'){
                                $siteClass = 'td-red';
                            }
                        @endphp

                        <table class="table table-bordered display-center" cellspacing="0">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <td colspan="1"><h3>{{ $groupCount++ }}</h3></td>
                                    <td colspan="8">
                                    <table class="table table-bordered full-sched-table" cellspacing="0">
                                        <tr>
                                            <td>ARENA NAME</td>
                                            <td colspan="3" class="td-green">{{ $group->name }}</td>
                                            <!-- <td class=""></td> -->
                                            <td colspan="3">{{ $group->remarks }}</td>
                                        </tr>
                                        <tr>
                                            <td>Address</td>
                                            <td colspan="2">{{ $group->address }}</td>
                                            <td class="td-green">{{ $group->code }}</td>
                                            <td colspan="2">Date</td>
                                            <td>Time</td>
                                        </tr>
                                        <tr>
                                            <td>Site</td>
                                            <td colspan="2">{{ $group->group_type }}</td>
                                            <td class="{{ $siteClass }}">{{ $group->site }}</td>
                                            <td colspan="2">{{ date('l, M d Y', strtotime($scheduleInfo->date_time)) }}</td>
                                            <td>{{ date('H:i A', strtotime($group->operation_time)) }}</td>
                                        </tr>
                                        <tr>
                                            <td>OPERATOR NAME</td>
                                            <td colspan="2">{{ strtoupper($group->owner) }}</td>
                                        </tr>
                                        <tr>
                                            <td>CONTACT DETAILS</td>
                                            <td colspan="2">{{ $group->contact }}</td>
                                        </tr>
                                        <tr>
                                            <td># OF PC INSTALLED</td>
                                            <td colspan="2">{{ $group->installed_pc }}</td>
                                        </tr>
                                        <tr>
                                            <td># OF ACTIVE STAFFS</td>
                                            <td colspan="2">{{ $group->active_staff }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="8"></td>
                                            </tr>
                                            <tr class="t-header">
                                            <th>NAMES</th>
                                            <th colspan="2">CONTACT</th>
                                            <th>POSITION</th>
                                            <th>REMARKS</th>
                                            <th>USERNAME</th>
                                            <th>PASSWORD</th>
                                            <th>STATUS</th>
                                        </tr>

                                        @if(isset($groupedByAccounts[$group->group_id]))
                                            @foreach($groupedByAccounts[$group->group_id] as $account)
                                            <tr>
                                                <td>{{ strtoupper($account['first_name']) }} {{ strtoupper($account['last_name']) }}</td>
                                                <td colspan="2">{{ $account['contact'] }}</td>
                                                <td>{{ strtoupper($account['position']) }}</td>
                                                <td>{{ $account['remarks'] }}</td>
                                                <td>{{ $account['username'] }}</td>
                                                <td></td>
                                                <td class="td-sblue">ACCOUNT CONFIRMED</td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan=7>No confirmed staff</td>
                                            </tr>
                                        @endif

                                    </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    @endforeach

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