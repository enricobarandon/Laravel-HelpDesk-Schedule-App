<table class="table table-bordered table-striped global-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Schedule Name</th>
            <th>Schedule Date</th>
            <th>Created At</th>
            <th>Finished At</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pastSchedules as $schedule)
            <tr>
                <td>{{ $schedule->id }}</td>
                <td>{{ $schedule->name }}</td>
                <td>{{ date("M d, Y",strtotime($schedule->date_time)) }}</td>
                <td>{{ date("M d, Y h:i:s a",strtotime($schedule->created_at)) }}</td>
                <td>{{ date("M d, Y h:i:s a",strtotime($schedule->updated_at)) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>