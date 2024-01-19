<table id="tblLogs" class="table table-bordered table-striped sm-global-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Bet Count</th>
            <th>Transaction Count</th>
            <th>Date Covered</th>
            <th>FFG Archive Link</th>
            <th>Schedule Archive Link</th>
            <th>Start</th>
            <th>End</th>
            <th>Duration</th>
            <th>Requested By</th>
            <th>Processed By</th>
            @if(empty($downloading))
                @if(Auth::user()->user_type_id == 1)
                <th>Action</th>
                @endif
            @endif
        </tr>
    </thead>
    <tbody>
    @php
        $i = ($rows->currentpage()-1)* $rows->perpage() + 1;
    @endphp
        @if(!$rows->isEmpty())

            @foreach($rows as $index => $value)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ number_format($value->bet_count) }}</td>
                    <td>{{ number_format($value->transaction_count) }}</td>
                    <td>{{ $value->date_covered }}</td>
                    <td>{{ $value->fg_link }}</td>
                    <td>{{ $value->schedule_link }}</td>
                    <td>{{ $value->start }}</td>
                    <td>{{ $value->end }}</td>
                    <td>{{ $value->duration }}</td>
                    <td>{{ $value->requested_by }}</td>
                    <td>{{ $value->processed_by }}</td>
                    @if(empty($downloading))
                        @if(Auth::user()->user_type_id == 1)
                        <td><a href="{{ url('/archive/update/'.$value->id) }}" class="btn btn-primary btn-sm">Edit</a></td>
                        @endif
                    @endif
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan='12' class="text-center">No Data</td>
            </tr>
        @endif
    </tbody>
</table>
