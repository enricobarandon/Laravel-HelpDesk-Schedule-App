@php
    $groupCount = 1;
@endphp

<table class="table table-bordered display-center" id="table_id" cellspacing="0">
    <thead></thead>
        @foreach($groups as $group)

            @php
                $siteClass = '';
                if ($group->site == 'WPC2040'){
                    $siteClass = 'background-color: blue; color: white;';
                } else if ($group->site == 'WPC2040AA'){
                    $siteClass = 'background-color: red; color: white;';
                }
            @endphp
            <tbody>

                    <tr>
                        <td colspan="1"><h3>{{ $groupCount++ }}</h3></td>
                        <td>
                            <table class="table table-bordered full-sched-table" cellspacing="0">
                                <thead>
                                    <tr>
                                        <td style="text-align: center;">ARENA NAME</td>
                                        <td colspan="3" style="background-color: darkgreen; color: white; font-weight: bold;" >{{ $group->name }}</td>
                                        <td colspan="4" style="text-align: center;">{{ $group->remarks }}</td>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td style="text-align: center;">Address</td>
                                    <td colspan="3" style="text-align: center;">{{ $group->address }}</td>
                                    <td colspan="2" style="background-color: darkgreen; color: white; text-align: center; font-weight: bold;">{{ $group->code }}</td>
                                    <td colspan="2" style="{{ $siteClass }} text-align: center; font-weight: bold;">{{ $group->site }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">Site</td>
                                    <td colspan="3" style="text-align: center;">{{ $group->group_type }}</td>
                                    <td colspan="2" style="text-align: center;">Date</td>
                                    <td colspan="2" style="text-align: center;">Time</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">OPERATOR NAME</td>
                                    <td colspan="3" style="text-align: center;">{{ strtoupper($group->owner) }}</td>
                                    <td colspan="2" style="text-align: center;">{{ date('l, M d Y', strtotime($scheduleInfo->date_time)) }}</td>
                                    <td colspan="2" style="text-align: center;">{{ date('H:i A', strtotime($group->operation_time)) }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">CONTACT DETAILS</td>
                                    <td colspan="3" style="text-align: center;">{{ $group->contact }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;"># OF PC INSTALLED</td>
                                    <td colspan="3" style="text-align: center;">{{ $group->installed_pc }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;"># OF ACTIVE STAFFS</td>
                                    <td colspan="3" style="text-align: center;">{{ $group->active_staff }}</td>
                                </tr>

                                <tr class="t-header">
                                    <th style="background-color: black; color: yellow; text-align: center;">NAMES</th>
                                    <th colspan="2" style="background-color: black; color: yellow; text-align: center;">CONTACT</th>
                                    <th style="background-color: black; color: yellow; text-align: center;">POSITION</th>
                                    <th style="background-color: black; color: yellow; text-align: center;">REMARKS</th>
                                    <th style="background-color: black; color: yellow; text-align: center;">USERNAME</th>
                                    <th style="background-color: black; color: yellow; text-align: center;">PASSWORD</th>
                                    <th style="background-color: black; color: yellow; text-align: center;">STATUS</th>
                                </tr>

                                @if(isset($groupedByAccounts[$group->group_id]))
                                    @foreach($groupedByAccounts[$group->group_id] as $account)
                                    <tr>
                                        <td style="text-align: center;">{{ strtoupper($account['first_name']) }} {{ strtoupper($account['last_name']) }}</td>
                                        <td colspan="2" style="text-align: center;">{{ $account['contact'] }}</td>
                                        <td style="text-align: center;">{{ strtoupper($account['position']) }}</td>
                                        <td style="text-align: center;">{{ $account['allowed_sides'] }}</td>
                                        <td style="text-align: center;">{{ $account['username'] }}</td>
                                        <td style="text-align: center;"></td>
                                        <td style="background-color: lightskyblue; text-align: center;">ACCOUNT CONFIRMED</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" style="text-align: center;">No confirmed staff</td>
                                    </tr>
                                @endif

                                </tbody>
                            </table>
                        </td>
                    </tr>
    </tbody>
        @endforeach
</table>