@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <a href='{{ url("schedules") }}' class="btn btn-primary"><< Back to Schedule Management page</a>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Schedule Management') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <router-view />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
