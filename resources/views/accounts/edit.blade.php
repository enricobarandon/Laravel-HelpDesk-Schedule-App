@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <!-- <a href='{{ url("schedules") }}' class="btn btn-primary"><< Back to Schedule Management page</a> -->

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Accounts Management - Under development') }}</div>

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

                    <form action="{{ route('storeAccountRequest') }}" method="post">
                        @csrf
                        <input type="hidden" name="uuid" value="{{ $account->uuid }}">
                        <input type="hidden" name="operation" value="users.update">

                        <div class="col-md-4">

                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" class="form-control" id="first-name" name="first-name" value="{{ $account->first_name }}">
                            </div>

                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" class="form-control" id="last-name" name="last-name" value="{{ $account->last_name }}">
                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="{{ $account->username }}">
                            </div>

                            <div class="form-group">
                                <label>Contact</label>
                                <input type="text" class="form-control" id="contact" name="contact" value="{{ $account->contact }}">
                            </div>
                            
                        </div>

                        <div class="col-md-4">

                            <div class="form-group">
                                <label>Position</label>
                                <input type="text" class="form-control" id="position" name="position" value="{{ $account->position }}">
                            </div>

                            <div class="form-group">
                                <label>Allowed Sides</label>
                                <!-- <input type="text" class="form-control" id="group-guarantor" name="group-guarantor"> -->
                                <select class="form-control" id="allowed-sides" name="allowed-sides">
                                    <option selected disabled> -- Select Allowed Sides --</option>
                                    <!-- <option value="m">Meron Only</option>
                                    <option value="w">Wala Only</option>
                                    <option value="a">All Sides</option>
                                    <option value="n">None</option> -->
                                    @foreach($allowedSides as $key => $side)
                                        <option value="{{ $key }}" <?php echo $account->allowed_sides == $side ? 'selected' : '' ?>>
                                            {{ $side }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="form-group">
                                <label>Status</label>
                                <label class="radio-active" for="active">
                                <input 
                                    type="radio"
                                    name="is-active" 
                                    id="active"
                                    value="1" 
                                    <?php echo $account->is_active ? 'checked' : '' ?>
                                >
                                Active</label>
                                <label class="radio-deactivated" for="deactivated">
                                <input 
                                    type="radio"
                                    name="is-active" 
                                    id="deactivated"
                                    value="0" 
                                    <?php echo $account->is_active ? '' : 'checked' ?>
                                >
                                Deactivated</label>
                            </div>

                        </div>

                        <input type="submit" class="btn btn-success" value="Submit Update Request">

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
