@extends('layouts.app')

@section('content')
@section('title','Accounts')
@php
if (! function_exists('removeParam')) {
    function removeParam($url, $param) {
        $url = preg_replace('/(&|\?)'.preg_quote($param).'=[^&]*$/', '', $url);
        $url = preg_replace('/(&|\?)'.preg_quote($param).'=[^&]*&/', '$1', $url);
        return $url;
    }
}
$allowedToCreate = [1,2,3];
$user = auth()->user();
@endphp
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fa fa-info-circle"></i> Accounts Page</h3>
                    <a class="btn btn-success float-right" href="{{ removeParam(request()->fullUrlWithQuery(['download' => '1']), 'downloadcurrent') }}">Download Excel</a>
                    @if(in_array($user->user_type_id, $allowedToCreate))
                        <a class="btn btn-primary float-right" href="{{ route('accounts.create') }}"><i class="fa fa-plus"></i> Create Account</a>
                    @endif
                </div>

                <div class="card-body">
                    @if (session('success'))
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
                                <input type="text" class="form-control" name="filterName" id="filterName" placeholder="Name / Username">
                            </div>

                            <div class="col-md-3">
                                <input type="text" class="form-control" name="filterGname" id="filterGname" placeholder="Group Code">
                            </div>
                            
                            <div class="col-md-2">
                                <select class="form-control" name="filterRole" id="filterStatus">
                                    <option selected disabled value="">SELECT ROLE</option>
                                    <option value="Teller/Cashier">Teller/Cashier</option>
                                    <option value="Teller">Teller</option>
                                    <option value="Cashier">Cashier</option>
                                    <option value="Supervisor">Supervisor</option>
                                    <option value="Operator">Operator</option>
                                </select>
                            </div>

                            <div class="col">
                                <button type="submit" class="btn btn-success"><i class="fas fa-search"></i> Submit</button>
                                <a href="{{ url('/accounts') }}" class="btn btn-danger">Reset</a>
                            </div>
                        </div>
                    </form>

                    <!-- <button type="button" class="btn btn-primary float-right"><i class="fas fa-plus"></i> Create Account</button> -->
                    <table class="table table-bordered table-striped sm-global-table">
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
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $accountCount = ($accounts->currentpage()-1)* $accounts->perpage() + 1;
                                $user = auth()->user();
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
                                    <td>
                                        <form method="post" action="{{ url('/accounts/update-password/'.$account->acc_id)}}">
                                        @csrf
                                            <div class="input-group">
                                                <input type="hidden" name="currentURL" value="{{ $_SERVER['REQUEST_URI'] }}">
                                                <input type="hidden" name="accountId" value="{{ $account->acc_id }}">
                                                <input class="form-control edit-password" name="password" type="text" disabled value="{{ $account->password }}"/>
                                                @if($user->user_type_id == 1 || $user->user_type_id == 2)
                                                <button type="button" class="update-password btn-secondary"><i class="fa fa-edit"></i></button>
                                                <a href="/accounts" class="cancel-update btn-danger"><i class="fa fa-times"></i></a>
                                                <button type="submit" class="submit-password btn-success"><i class="fa fa-check"></i></button>
                                                @endif
                                            </div>
                                        </form>
                                    </td>
                                    <td class="display-center">
                                        <a href="/accounts/{{ $account->acc_id }}" type="button" class="btn btn-xs btn-primary"><i class="fas fa-cog"></i> Edit</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="col">
                        <div class="float-right">
                            {{ $accounts->appends(Request::except('page'))->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
$("document").ready(function(){
    $('.update-password').on('click',function(){
        $(this).closest('.input-group').find('.submit-password').show();
        $(this).closest('.input-group').find('.cancel-update').show();
        $(this).closest('.input-group').find('.edit-password').prop('disabled', false).css('background', '#ffffff').focus();
        $(this).hide();
    })
});
</script>
@endsection