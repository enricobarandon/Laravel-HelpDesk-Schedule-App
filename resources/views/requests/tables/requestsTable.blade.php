<table class="table table-bordered table-striped global-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Account/Group Name</th>
            <th>Operation</th>
            <th>Status</th>
            <th>Requested Data</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @php
            $requestCount = ($requests->currentpage()-1)* $requests->perpage() + 1;
        @endphp
        @foreach($requests as $request)
            <tr>
                <td>{{ $requestCount++ }}</td>
                <td>
                    @php
                        echo $request->group_name ? htmlspecialchars($request->group_name) : $request->username
                    @endphp
                </td>
                <td>{{ $request->operation }}</td>
                <td>{{ $request->status }}</td>
                <td>
                    @php
                        $dataHtml = '';
                        if ($request->data != 'null') {
                            $data = json_decode($request->data);
                            $dataHtml = '';
                            foreach($data as $key => $value) {
                                $dataHtml .= "<li>$key : $value</li>";
                            }
                        }
                    @endphp
                    {!! $dataHtml !!}
                </td>
                <td>{{ $request->remarks }}</td>
            </tr>
        @endforeach
    </tbody>
</table>