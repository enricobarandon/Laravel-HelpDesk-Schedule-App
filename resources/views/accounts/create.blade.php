@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="justify-content-center">
        <div class="col-md-12">
            <a href='{{ url("accounts") }}' class="btn btn-primary"><< Back to Accounts Page</a>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fa fa-info-circle"></i> Accounts Management</h3>
                </div>

                <div class="card-body">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('storeAccountRequest') }}" class="row" method="post">
                        @csrf
                        <input type="hidden" name="operation" value="users.create">

                        <div class="col-md-6">

                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" class="form-control" id="first-name" name="first-name" value="{{ old('first-name') }}">
                            </div>

                        </div>
                        
                        <div class="col-md-6">

                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" class="form-control" id="last-name" name="last-name" value="{{ old('last-name') }}">
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" class="form-control" id="username" name="username">
                            </div>
                            
                        </div>
                        
                        <div class="col-md-6">

                        <div class="form-group">
                                <label>Contact</label>
                                <input type="number" class="form-control" id="contact" name="contact" value="{{ old('contact') }}">
                            </div>

                        </div>
                        
                        <div class="col-md-6">

                            <div class="form-group">
                                    <label>Group</label>
                                    <select class="form-control" id="group-code" name="group-code">
                                        <option selected disabled> -- Select Group --</option>
                                        @foreach($groups as $group)
                                            <option value="{{ $group->code }}">
                                                {{ $group->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                        </div>
                        
                        <div class="col-md-6">

                            <div class="form-group">
                                <label>Position</label>
                                <select class="form-control" id="position" name="position">
                                    <option selected disabled> -- Select Position --</option>
                                    @foreach($positions as $position)
                                        <option value="{{ $position }}">
                                            {{ $position }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label>Allowed Sides</label>
                                <select class="form-control" id="allowed-sides" name="allowed-sides">
                                    <option selected disabled> -- Select Allowed Sides --</option>
                                    @foreach($allowedSides as $key => $side)
                                        <option value="{{ $key }}">
                                            {{ $side }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label>Status: </label>
                                <div class="form-control">
                                    <label class="radio-active" for="active">
                                    <input 
                                        type="radio"
                                        name="is-active" 
                                        id="active"
                                        value="1">
                                    Active</label>
                                    <label class="radio-deactivated" for="deactivated">
                                    <input 
                                        type="radio"
                                        name="is-active" 
                                        id="deactivated"
                                        value="0">
                                    Deactivated</label>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label>Request Remarks</label>
                                <textarea id="remarks" name="remarks" class="form-control" rows="2" >{{ old('remarks') }}</textarea>
                            </div>
                            
                        </div>

                        <div class="col-md-12 text-center">
                            
                            <input type="submit" class="btn btn-success" value="Submit Create Request">
                        
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
