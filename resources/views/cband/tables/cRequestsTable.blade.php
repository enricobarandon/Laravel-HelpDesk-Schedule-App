@php
$user = auth()->user();
$allowedRolesForActions = [5];
@endphp
<table class="table table-bordered table-striped sm-global-table">
    <thead>
        <tr>
            <th>#</th>
            <th>ID</th>
            <th>Ref #</th>
            <th>Requested At</th>
            <th>Requested By</th>
            <th>Group Name</th>
            <th>Operation</th>
            <th>Status</th>
            <th>Requested Data</th>
            <th>Remarks</th>
            <th>Current Viewing Status</th>
            <th>Action</th>
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
                $viewingStatus = $request->viewing_status ? 'Active' : 'Deactivated';

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
                                $dataHtml .= "$key : $value";
                            }
                        }
                    @endphp
                    {!! htmlspecialchars($dataHtml) !!}
                </td>
                <td>{{ $request->remarks }}</td>
                <td class="{{ $viewingStatus == 'Active' ? 'td-blue' : 'td-red' }}">{{ $viewingStatus }}</td>
                <td>
                    @if($request->status == 'approved')
                        @if($request->is_processed == '0')
                        <span class="span-red">Processing</span>
                        @else
                        <span class="span-green">Processed</span>
                        @endif
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>