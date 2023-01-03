@extends('layouts.app')

@section('content')
@section('title','C-Band')
@php
if (! function_exists('removeParam')) {
    function removeParam($url, $param) {
        $url = preg_replace('/(&|\?)'.preg_quote($param).'=[^&]*$/', '', $url);
        $url = preg_replace('/(&|\?)'.preg_quote($param).'=[^&]*&/', '$1', $url);
        return $url;
    }
}
@endphp
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fa fa-info-circle"></i> Cband</h3>
                    <a class="btn btn-success float-right" href="{{ removeParam(request()->fullUrlWithQuery(['download' => '1']), 'downloadcurrent') }}">Download Excel</a>
                </div>
                 <div class="card-body">
                    
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @elseif (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @elseif(session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form class="form-horizontal" method="get">
                        <div class="form-group row">

                            <div class="col-md-3">
                                <input type="text" class="form-control" name="keyword" id="keyword" placeholder="keyword" value="">
                            </div>

                            <div class="col-md-2">
                                <select class="form-control" name="status" id="status">
                                    <option value="" {{ $status == '' ? 'selected' : '' }} disabled>Select All Status</option>
                                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <select class="form-control" name="cbandStatus" id="cbandStatus">
                                    <option value="0" {{ $cbandStatus == '0' ? 'selected' : '' }}>Processing</option>
                                    <option value="1" {{ $cbandStatus == '1' ? 'selected' : '' }}>Processed</option>
                                </select>
                            </div>
                            
                            <div class="col">
                                <button type="submit" class="btn btn-success"><i class="fas fa-search"></i> Submit</button>
                                <a href="{{ url('/cband?cbandStatus=0') }}" class="btn btn-danger">Reset</a>
                            </div>
                        </div>
                    </form>

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
                                    $viewingStatus = '';
                                    $statusClass = '';
                                    if($request->viewing_status != ''){
                                        if($request->viewing_status == 0){
                                            $viewingStatus = 'Deactivated';
                                            $statusClass = 'td-red';
                                        }else{
                                            $viewingStatus = 'Active';
                                            $statusClass = 'td-blue';
                                        }
                                    }else{
                                        $statusClass = '';
                                        $viewingStatus = '--';
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
                                            $is_active = '';
                                            if ($request->data != 'null') {
                                                $data = json_decode($request->data);
                                                $dataHtml = '';
                                                foreach($data as $key => $value) {
                                                    $dataHtml .= "<li>$key : $value</li>";
                                                    $is_active = $data->is_active;
                                                }
                                            }
                                        @endphp
                                        {!! $dataHtml !!}
                                        <!-- {{ $is_active }} -->
                                    </td>
                                    <td>{{ $request->remarks }}</td>
                                    <td class="{{ $statusClass }}">{{ $viewingStatus }}</td>
                                    <td class="text-center">
                                        @if (in_array($user->user_type_id, $allowedRolesForActions))
                                            @if($request->status == 'approved')
                                                @if($request->is_processed == '0')
                                                    @if($is_active == '1')
                                                    <form method="POST" action='{{ url("/cband") }}'>
                                                        @csrf
                                                        <input type="hidden" name="group_id" value="{{ $request->group_id }}">
                                                        <input type="hidden" name="request_id" value="{{ $request->id }}">
                                                        <input type="hidden" name="request_action" value="activate">
                                                        <button type="button" class="btn btn-success btn-sm btn-status-update">Activate</button>
                                                    </form>
                                                    @elseif($is_active == '0')
                                                    <form method="POST" action='{{ url("/cband") }}'>
                                                        @csrf
                                                        <input type="hidden" name="group_id" value="{{ $request->group_id }}">
                                                        <input type="hidden" name="request_id" value="{{ $request->id }}">
                                                        <input type="hidden" name="request_action" value="deactivate">
                                                        <button type="button" class="btn btn-danger btn-sm btn-status-update">Deactivate</button>
                                                    </form>
                                                    @else
                                                        --
                                                    @endif
                                                @else
                                                <span class="span-green">Processed</span>
                                                @endif
                                            @endif
                                        @else
                                            @if($request->status == 'approved')
                                                @if($request->is_processed == '0')
                                                <span class="span-red">Processing</span>
                                                @else
                                                <span class="span-green">Processed</span>
                                                @endif
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="col">
                        <div class="float-right">
                            {{ $requests->appends(Request::except('page'))->links('pagination::bootstrap-4') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $("document").ready(function(){
    $('.btn-status-update').on('click', function(){
        if($(this).text() == 'Activate'){
            $text = 'Activate';
        }else{
            $text = 'Deactivate';
        }
        swal({
            title: "Are you sure?",
            text: "You want to "+$text+" Viewing of this group?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willUpdate) => {
            if (willUpdate) {
                $(this).closest('form').submit();
            }
          });
    })
});
</script>
@endsection