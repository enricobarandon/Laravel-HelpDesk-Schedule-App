@extends('layouts.app')

@php
if (! function_exists('removeParam')) {
    function removeParam($url, $param) {
        $url = preg_replace('/(&|\?)'.preg_quote($param).'=[^&]*$/', '', $url);
        $url = preg_replace('/(&|\?)'.preg_quote($param).'=[^&]*&/', '$1', $url);
        return $url;
    }
}
@endphp
@section('content')
@section('title','Users')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fa fa-info-circle"></i> Users Page</h3>
                    <a class="btn btn-success float-right" href="{{ removeParam(request()->fullUrlWithQuery(['download' => '1']), 'downloadcurrent') }}">Download Excel</a>
                    <a href='/register' class="btn btn-primary float-right"><i class="fas fa-plus"></i> Create User</a>
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
                                <input type="text" class="form-control" name="keyword" id="keyword" placeholder="keyword" value="{{ $keyword }}">
                            </div>

                            <div class="col-md-3">
                                <select class="form-control" name="userType" id="userType">
                                    <option value="" selected disabled>Select All Type</option>
                                    @foreach($userTypes as $type)
                                    <option value="{{ $type->id }}" {{ $userType == $type->id ? 'selected' : '' }}>{{ $type->role }}</option>
                                    @endforeach
                                    
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <select class="form-control" name="userStatus" id="userStatus">
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="1" {{ $userStatus == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $userStatus == '0' ? 'selected' : '' }}>Deactivated</option>
                                    
                                </select>
                            </div>

                            <div class="col">
                                <button type="submit" class="btn btn-success"><i class="fas fa-search"></i> Submit</button>
                                <a href="{{ url('/users') }}" class="btn btn-danger">Reset</a>
                            </div>
                        </div>
                    </form>

                    <table class="table table-bordered table-striped global-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>User Role</th>
                                <th>Created At</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $usersCount = ($users->currentpage()-1)* $users->perpage() + 1;
                            @endphp
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $usersCount++ }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>{{ date("M d, Y h:i:s a",strtotime($user->created_at)) }}</td>
                                    <td>
                                        @if($user->is_active)
                                            Active
                                        @else
                                            Deactivated
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <form action='{{ url("/users/$user->id") }}' method="POST">
                                            @csrf
                                                @if($user->is_active)
                                                    <button type="button" class="btn btn-danger users-status deactivate">
                                                        <i class="fas fa-times"></i> Deactivate
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-success users-status activate">
                                                        <i class="fas fa-plus"></i> Activate
                                                    </button>
                                                @endif
                                        </form>
                                        <a href="{{ url('users/update') }}/{{ $user->id }}/info" name="updateUser" class="btn btn-primary"><i class="fas fa-cog"></i> Edit</a>
                                        <a href="{{ url('users/update') }}/{{ $user->id }}/password" name="updateUser" class="btn btn-info"><i class="fas fa-cog"></i> Change Password</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                                
                    <div class="col">
                        <div class="float-right">
                            {{ $users->appends(['userType' => $userType])->links('pagination::bootstrap-4') }}
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
    $('.users-status').on('click', function(){
        if($(this).hasClass('activate') == true){
            $text = 'Activate';
        }else{
            $text = 'Deactivate';
        }
        swal({
            title: "Are you sure?",
            text: "You want to "+$text+" User?",
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