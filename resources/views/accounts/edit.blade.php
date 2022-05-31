@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <!-- <a href='{{ url("schedules") }}' class="btn btn-primary"><< Back to Schedule Management page</a> -->

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Accounts Management - Under development') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

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

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
