@extends('layouts.app')

@section('content')
@section('title','Create Archive')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
        <a href="{{ url('/archive') }}" class="btn btn-primary">Back to Archive Link Page</a>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fa fa-info-circle"></i> Update Archive</h3>
                    {{-- <a class="btn btn-primary float-right" href="{{ route('accounts.create') }}"><i class="fa fa-plus"></i> Create Account</a> --}}
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

                    <form action="{{ route('archive.submitArchive') }}" class="row" method="post">
                        @csrf
                        <input type="hidden" name="operation" value="update">
                        <input type="hidden" name="id" value="{{ $archiveInfo->id }}">

                        <div class="col-md-6">

                            <div class="form-group">
                                <label>Bet Count</label>
                                <input type="text" class="form-control" id="bet_count" name="bet_count" value="{{ $archiveInfo->bet_count }}" required>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label>Transaction Count</label>
                                <input type="text" class="form-control" id="transaction_count" name="transaction_count" value="{{ $archiveInfo->transaction_count }}" required>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label>Date Covered</label>
                                <input type="text" class="form-control" id="date_covered" name="date_covered" value="{{ $archiveInfo->date_covered }}" required>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label>Sabong Archive Link</label>
                                <input type="text" class="form-control" id="fg_link" name="fg_link" value="{{ $archiveInfo->fg_link }}" required>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label>Kiosk Archive Link</label>
                                <input type="text" class="form-control" id="schedule_link" name="schedule_link" value="{{ $archiveInfo->schedule_link }}" required>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="schedLink">Date/Time Start</label>
                                <input type="text" class="form-control" id="start" name="start" value="{{ $archiveInfo->start }}" required>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="end">Date/Time End</label>
                                <input type="text" class="form-control" id="end" name="end" value="{{ $archiveInfo->end }}" required>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label>Duration</label>
                                <input type="text" class="form-control" id="duration" name="duration" value="{{ $archiveInfo->duration }}" required>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label>Requested By</label>
                                <input type="text" class="form-control" id="requested_by" name="requested_by" value="{{ $archiveInfo->requested_by }}" required>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label>Processed By</label>
                                <input type="text" class="form-control" id="processed_by" name="processed_by" value="{{ $archiveInfo->processed_by }}" required>
                            </div>

                        </div>

                        <div class="col-md-12 text-center">

                            <input type="submit" class="btn btn-success" value="Submit ">
                            <a href="{{ url('/archive') }}" class="btn btn-danger ml-3">Cancel</a>

                        </div>

                    </form>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
@endsection

@section('script')
@endsection
