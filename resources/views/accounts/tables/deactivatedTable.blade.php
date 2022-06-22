<table class="table table-bordered table-striped global-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Group</th>
            <th>Full Name</th>
            <th>Contact #</th>
            <th>Role</th>
            <th>Username</th>
            <th>Password</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @php
            $accountCount = ($accounts->currentpage()-1)* $accounts->perpage() + 1;
        @endphp
        @foreach($accounts as $account)
            <tr>
                <td>{{ $accountCount++ }}</td>
                <td>{{ htmlspecialchars($account->group_name) }}</td>
                <!-- <td>--</td> -->
                <td>{{ $account->first_name }} {{ $account->last_name }}</td>
                <td>{{ $account->contact }}</td>
                <td>{{ $account->position }}</td>
                <td>{{ $account->username }}</td>
                <td>{{ $account->password }}</td>
                <td>{{ $account->status }}</td>
                <td class="display-center">
                </td>
            </tr>
        @endforeach
    </tbody>
</table>