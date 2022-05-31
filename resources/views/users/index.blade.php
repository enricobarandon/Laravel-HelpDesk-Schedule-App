@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">{{ __('Users Page') }}</div>

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

                    <a href='/register' class="btn btn-primary"><i class="fas fa-plus"></i> Create User</a>
                    <table class="table table-bordered table-striped global-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>User Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $usersCount = 1;
                            @endphp
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $usersCount++ }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td class="text-center">
                                        <a href="{{ url('users/update') }}/{{ $user->id }}/info" name="updateUser" class="btn btn-primary"><i class="fas fa-cog"></i> Edit</a>
                                        
                                        <a href="{{ url('users/update') }}/{{ $user->id }}/password" name="updateUser" class="btn btn-info"><i class="fas fa-cog"></i> Change Password</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                                        

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
