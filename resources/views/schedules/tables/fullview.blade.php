@php
    $groupCount = 1;
@endphp

<table class="table table-bordered display-center" cellspacing="0">
    <thead></thead>
    <tbody>
        @foreach($groups as $group)

            @php
                $siteClass = '';
                if ($group->site == 'wpc2040'){
                    $siteClass = 'td-blue';
                } else if ($group->site == 'wpc2040aa'){
                    $siteClass = 'td-red';
                }
            @endphp

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
        @endforeach
    </tbody>
</table>