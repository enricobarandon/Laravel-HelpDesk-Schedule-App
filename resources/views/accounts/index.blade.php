@extends('layouts.app')

@section('content')
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
                <div class="card-header">{{ __('Schedule Management') }}</div>

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
                                        <h3 class="card-title"><i class="fa fa-info-circle"></i> Accounts Page</h3>
                                        <a class="btn btn-success float-right" href="{{ removeParam(request()->fullUrlWithQuery(['download' => '1']), 'downloadcurrent') }}">Download Excel</a>
                                        <a class="btn btn-primary float-right" href="{{ route('accounts.create') }}"><i class="fa fa-plus"></i> Create Account</a>
                                    </div>
                                    <div class="card-body">

                                        <form class="form-horizontal" method="get">
                                            <div class="form-group row">

                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" name="filterName" id="filterName" placeholder="Name / Username">
                                                </div>

                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" name="filterGname" id="filterGname" placeholder="Group Code">
                                                </div>

                                                <div class="col-md-2">
                                                    <select class="form-control" name="filterStatus" id="filterStatus">
                                                        <option selected disabled value="">SELECT STATUS</option>
                                                        <option value="1" {{ $filterStatus == '1' ? 'selected' : '' }}>ACTIVE</option>
                                                        <option value="0" {{ $filterStatus == '0' ? 'selected' : '' }}>DEACTIVATED</option>
                                                    </select>
                                                </div>

                                                <div class="col">
                                                    <button type="submit" class="btn btn-success"><i class="fas fa-search"></i> Submit</button>
                                                    <a href="{{ url('/accounts') }}" class="btn btn-danger">Reset</a>
                                                </div>
                                            </div>
                                        </form>

                                        <!-- <button type="button" class="btn btn-primary float-right"><i class="fas fa-plus"></i> Create Account</button> -->
                                        <table class="table table-bordered table-striped global-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Group</th>
                                                    <!-- <th>Guarantor</th> -->
                                                    <th>Full Name</th>
                                                    <th>Contact #</th>
                                                    <th>Role</th>
                                                    <th>Username</th>
                                                    <th>Password</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $accountCount = ($accounts->currentpage()-1)* $accounts->perpage() + 1;
                                                @endphp
                                                @foreach($accounts as $account)
                                                    <tr>
                                                        <td>{{ $accountCount++ }}</td>
                                                        <td>{{ htmlspecialchars($account->group_name) }}</td>
                                                        <!-- <td>--</td> -->
                                                        <td>{{ $account->first_name }} {{ $account->last_name }}</td>
                                                        <td>{{ $account->contact }}</td>
                                                        <td>{{ $account->position }}</td>
                                                        <td>{{ $account->username }}</td>
                                                        <td>--</td>
                                                        <td>{{ $account->is_active ? 'Active' : 'Deactivated'}}</td>
                                                        <td class="display-center">
                                                        <a href="/accounts/{{ $account->acc_id }}" type="button" class="btn btn-sm btn-primary"><i class="fas fa-cog"></i> Edit</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <div class="col">
                                            <div class="float-right">
                                                {{ $accounts->appends(['filterStatus' => $filterStatus])->links('pagination::bootstrap-4') }}
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
