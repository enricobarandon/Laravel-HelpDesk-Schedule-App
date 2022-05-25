@extends('layouts.app')

@section('content')

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
                                    </div>
                                    <div class="card-body">

                                        <form class="form-horizontal">
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <input type="text" class="filter-group form-control" placeholder="Group Name / Code">
                                                </div>

                                                <div class="col-md-3">
                                                    <input type="text" class="filter-group form-control" placeholder="Name / Username">
                                                </div>

                                                <div class="col-md-2">
                                                    <select id="selectStatus" name="selectStatus" class="form-control">
                                                        <option selected disabled value="">SELECT STATUS</option>
                                                        <option value="active">ACTIVE</option>
                                                        <option value="deactivated">Inactive</option>
                                                    </select>
                                                </div>

                                                <div class="col">
                                                    <button type="button" class="btn btn-success"><i class="fas fa-search"></i> Submit</button>
                                                </div>
                                            </div>
                                        </form>

                                        <button type="button" class="btn btn-primary float-right"><i class="fas fa-plus"></i> Create Account</button>
                                        <table class="table table-bordered table-striped global-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Group</th>
                                                    <th>Guarantor</th>
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
                                                    $accountCount = 1;
                                                @endphp
                                                @foreach($accounts as $account)
                                                    <tr>
                                                        <td>{{ $accountCount++ }}</td>
                                                        <td>{{ $account->group_name }}</td>
                                                        <td>--</td>
                                                        <td>{{ $account->first_name }} {{ $account->last_name }}</td>
                                                        <td>{{ $account->contact }}</td>
                                                        <td>{{ $account->position }}</td>
                                                        <td>{{ $account->username }}</td>
                                                        <td>--</td>
                                                        <td>{{ $account->is_active ? 'Active' : 'Inactive'}}</td>
                                                        <td class="display-center">
                                                        <button type="button" class="btn btn-secondary"><i class="fas fa-cog"></i> Update Account</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {{ $accounts->links() }}
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
