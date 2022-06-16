@php
$user = auth()->user();
$allowedRolesForActions = [1,5];
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
            <th>Viewing Status</th>
            @if(in_array($user->user_type_id, $allowedRolesForActions))
                <th>Action</th>
            @endif
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
                                $dataHtml .= "<li>$key : $value</li>";
                            }
                        }
                    @endphp
                    {!! $dataHtml !!}
                </td>
                <td>{{ $request->remarks }}</td>
                <td>{{ $viewingStatus }}</td>
                @if(in_array($user->user_type_id, $allowedRolesForActions))
                    <td>
                        @if ($request->status == 'approved' && in_array($user->user_type_id, $allowedRolesForActions))
                            <form method="POST" action='{{ url("/cband") }}'>
                                @csrf
                                <input type="hidden" name="group_id" value="{{ $request->group_id }}">
                                <input type="hidden" name="request_id" value="{{ $request->id }}">
                                <input type="hidden" name="request_action" value="activate">
                                <button type="submit" class="btn btn-success btn-sm">Activate</button>
                            </form>

                            <form method="POST" action='{{ url("/cband") }}'>
                                @csrf
                                <input type="hidden" name="group_id" value="{{ $request->group_id }}">
                                <input type="hidden" name="request_id" value="{{ $request->id }}">
                                <input type="hidden" name="request_action" value="deactivate">
                                <button type="submit" class="btn btn-danger btn-sm">Deactivate</button>
                            </form>
                        @endif
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>