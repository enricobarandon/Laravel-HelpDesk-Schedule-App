<table class="table table-bordered global-table">

    <thead>
        <tr>
            <th>#</th>
            <th>Group Name</th>
            <th>Code</th>
            <th>Site</th>
            <th>Province</th>
            <th>Operation Time</th>
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
                </tr>
            @endforeach
        @else
            <tr><td colspan=7 class="text-center">No Data</td></tr>
        @endif
    </tbody>
</table>