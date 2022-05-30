@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Requests') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <main class="py-4">

                                <div class="card card-info">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fa fa-info-circle"></i> Requests Page</h3>
                                    </div>
                                    <div class="card-body">

                                        <table class="table table-bordered table-striped global-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Target</th>
                                                    <th>Operation</th>
                                                    <th>Status</th>
                                                    <th>Requested Data</th>
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $requestCount = 1;
                                                @endphp
                                                @foreach($requests as $request)
                                                    <tr>
                                                        <td>{{ $requestCount++ }}</td>
                                                        <td>
                                                            @php
                                                                echo $request->group_name ? $request->group_name : $request->username
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
                                        
                                        <div class="col">
                                            <div class="float-right">
                                                {{ $requests->links('pagination::bootstrap-4') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                </main>
                            </div>
                            <!-- /.row -->
                        </div><!-- /.container-fluid -->
                    </div>
                    <!-- /.content -->

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
