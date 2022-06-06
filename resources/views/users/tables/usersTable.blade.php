<table class="table table-bordered table-striped global-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>User Role</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @php
            $usersCount = 1;
        @endphp
        @foreach($users as $user)
            <tr>
                <td>{{ $usersCount++ }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>{{ date("M d, Y h:i:s a",strtotime($user->created_at)) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>