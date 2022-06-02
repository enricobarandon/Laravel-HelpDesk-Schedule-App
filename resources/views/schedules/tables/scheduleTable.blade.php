<table class="table table-bordered table-striped global-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Schedule Name</th>
            <th>Schedule Date</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @php
            $scheduleCount = 1;
        @endphp
        @foreach($schedules as $schedule)
            <tr>
                <td>{{ $scheduleCount++ }}</td>
                <td>{{ $schedule->name }}</td>
                <td>{{ date("M d, Y",strtotime($schedule->date_time)) }}</td>
                <td>{{ date("M d, Y h:i:s a",strtotime($schedule->created_at)) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>