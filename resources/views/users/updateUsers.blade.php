@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <a href='{{ url("users") }}' class="btn btn-primary"><< Back to Users page</a>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Update Users Account') }}</div>

                <div class="card-body">
                    @if($operation == 'info')
                    <form method="POST" action="/submitUser">
                    {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $usersInfo->id }}">
                        <input type="hidden" name="operation" value="{{ $operation }}">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $usersInfo->name }}" required autocomplete="name" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="user_type_id" class="col-md-4 col-form-label text-md-end">{{ __('User Role') }}</label>

                            <div class="col-md-6">
                                <select id="user_type_id" class="form-control @error('user_type_id') is-invalid @enderror" name="user_type_id">
                                    <option selected disabled value="0">-- Select User Role--</option>
                                    @foreach($userTypes as $userType)
                                        <option value="{{ $userType->id }}" {{ $userType->id == $usersInfo->user_type_id ? 'selected' : '' }}>{{ $userType->role }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $usersInfo->email }}" required autocomplete="email">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                    @elseif($operation == 'password')
                    <form method="POST" action="/submitUser">
                    {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $usersInfo->id }}">
                        <input type="hidden" name="operation" value="{{ $operation }}">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">New Password</label>

                            <div class="col-md-6">
                                <input id="cpassword" type="password" class="form-control" name="cpassword" required autofocus>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="ccpassword" type="password" class="form-control" name="ccpassword" required>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
