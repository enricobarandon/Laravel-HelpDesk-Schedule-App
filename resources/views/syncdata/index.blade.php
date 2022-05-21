@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Data Synchronization') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if(Auth::user()->email == 'enricobarandon@gmail.com')

                        <form class="form-inline" method="post" action="{{ route('data.initialGroupsTransfer') }}">
                            @csrf
                            <p>Initial Groups transfer</p>
                            <button type="submit" class="btn btn-primary mb-2" id="btnSyncUsers">Confirm</button>
                        </form>

                        <form class="form-inline" method="post" action="{{ route('data.initialUsersTransfer') }}">
                            @csrf
                            <p>Initial users transfer</p>
                            <button type="submit" class="btn btn-primary mb-2" id="btnSyncUsers">Confirm</button>
                        </form>

                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
