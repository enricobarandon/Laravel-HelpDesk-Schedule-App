<table class="table table-bordered table-striped global-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Group Name</th>
            <th>Address</th>
            <th>Group Type</th>
            <th>Owner</th>
            <th>Contact</th>
            <th>Group Code</th>
            <th>Site</th>
            <th>Province</th>
            <th>Guarantor</th>
            <th>Operation Date</th>
            <th>Pullout Date</th>
        </tr>
    </thead>
    <tbody>
        @php
            $groupsCount = 1;
        @endphp
        @foreach($groups as $group)
            <tr>
                <td>{{ $groupsCount++ }}</td>
                <td>{{ $group->name }}</td>
                <td>{{ $group->address }}</td>
                <td>{{ htmlspecialchars($group->group_type) }}</td>
                <td>{{ htmlspecialchars($group->owner) }}</td>
                <td>{{ $group->contact }}</td>
                <td>{{ $group->code }}</td>
                <td>{{ $group->site }}</td>
                <td>{{ $group->province }}</td>
                <td>{{ $group->guarantor }}</td>
                <td>{{ $group->operation_date == '' ? '--' : date("M d, Y",strtotime($group->operation_date))}}</td>
                <td>{{ $group->pullout_date == '' ? '--' : date("M d, Y",strtotime($group->pullout_date))}}</td>
            </tr>
        @endforeach
    </tbody>
</table>