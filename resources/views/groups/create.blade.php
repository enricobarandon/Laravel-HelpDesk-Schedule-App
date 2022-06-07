@extends('layouts.app')

@section('content')
<div class="row">

    <form method="post" action="{{ route('storeGroupRequest') }}">
        @csrf
        <input type="hidden" name="operation" value="groups.create">

        <div class="col-md-6">
            <div class="form-group">
                <label>Group Name</label>
                <textarea id="group-name" name="group-name" class="form-control" rows="2"></textarea>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Address</label>
                <textarea id="group-address" name="group-address" class="form-control" rows="2"></textarea>
            </div>
        </div>


        <div class="col-md-4">

            <div class="form-group">
                <label>Group Type</label>
                <input type="text" class="form-control" id="group-type" name="group-type">
            </div>

            <div class="form-group">
                <label>Group Code</label>
                <input type="text" class="form-control" id="group-code" name="group-code">
            </div>

        </div>

        <div class="col-md-4">

            <div class="form-group">
                <label>Operator</label>
                <input type="text" class="form-control" id="group-operator" name="group-operator">
            </div>

            <div class="form-group">
                <label>Province</label>
                <!-- <input type="text" class="form-control" id="group-site" name="group-site"> -->
                <select class="form-control" id="province-id" name="province-id">
                    <option selected disabled>--Select Province--</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                    @endforeach
                </select>
            </div>
            
        </div>

        <div class="col-md-4">

            <div class="form-group">
                <label>Contact</label>
                <input type="text" class="form-control" id="group-contact" name="group-contact">
            </div>

            <div class="form-group">
                <label>Guarantor</label>
                <input type="text" class="form-control" id="group-guarantor" name="group-guarantor">
            </div>

        </div>

        <div class="col-md-4">
            <label>Status</label>
            <div class="form-control text-center">
                <label class="radio-active" for="active">
                <input 
                    type="radio"
                    name="is_active" 
                    id="active"
                    value="1" >
                Active</label>
                <label class="radio-deactivated" for="deactivated">
                <input 
                    type="radio"
                    name="is_active" 
                    id="deactivated"
                    value="0" >
                Deactivated</label>
            </div>
        </div>

        <div class="col-md-8">
            <div class="form-group">
                <label>Remarks</label>
                <textarea id="remarks" name="remarks" class="form-control" rows="2"></textarea>
            </div>
        </div>

        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Submit Create Request</button>
        </div>
    </form>
</div>

@endsection