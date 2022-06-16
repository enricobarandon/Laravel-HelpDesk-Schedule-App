
<table class="table table-bordered table-striped sm-global-table">
    <thead>
        <tr>
            <th>#</th>
            <th>ID</th>
            <th>Ref #</th>
            <th>Requested At</th>
            <th>Requested By</th>
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
            @php
                $tdClass = '';
                if($request->status == 'approved'){
                    $tdClass = 'td-green';
                }elseif($request->status == 'rejected'){
                    $tdClass = 'td-red';
                }else{
                    $tdClass = 'td-blue';
                }
            @endphp
            <tr>
                <td>{{ $requestCount++ }}</td>
                <td>{{ $request->id }}</td>
                <td>{{ $request->reference_number }}</td>
                <td>{{ date('M d, Y h:i:s A', strtotime($request->created_at)) }}</td>
                <td>{{ $request->requested_by }}</td>
                <td>
                    @php
                        echo $request->group_name ? htmlspecialchars($request->group_name) : $request->username
                    @endphp
                </td>
                <td>{{ $request->operation }}</td>
                <td class="{{ $tdClass }}">{{ strtoupper($request->status) }}</td>
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