<table class="table table-bordered table-striped global-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Group Name</th>
            <th>Group Type</th>
            <th>Owner</th>
            <th>Contact</th>
            <th>Group Code</th>
            <th>Site</th>
            <th>Province</th>
            <th>Active Staff</th>
            <th>Installed PC</th>
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
                <td>{{ htmlspecialchars($group->group_type) }}</td>
                <td>{{ htmlspecialchars($group->owner) }}</td>
                <td>{{ $group->contact }}</td>
                <td>{{ $group->code }}</td>
                <td>{{ $group->site }}</td>
                <td>{{ $group->province }}</td>
                <td>{{ $group->active_staff }}</td>
                <td>{{ $group->installed_pc }}</td>
            </tr>
        @endforeach
    </tbody>
</table>