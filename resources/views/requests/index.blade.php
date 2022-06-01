@extends('layouts.app')

@section('content')
@php
if (! function_exists('removeParam')) {
    function removeParam($url, $param) {
        $url = preg_replace('/(&|\?)'.preg_quote($param).'=[^&]*$/', '', $url);
        $url = preg_replace('/(&|\?)'.preg_quote($param).'=[^&]*&/', '$1', $url);
        return $url;
    }
}
@endphp
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('Requests') }}
                    <a class="btn btn-success float-right" href="{{ removeParam(request()->fullUrlWithQuery(['download' => '1']), 'downloadcurrent') }}">Download Excel</a>
                </div>
                 <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Main content -->
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <main class="py-4">

                                <div class="card card-info">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fa fa-info-circle"></i> Requests Page</h3>
                                    </div>
                                    <div class="card-body">

                                        @include('requests.tables.requestsTable');
                                        
                                        <div class="col">
                                            <div class="float-right">
                                                {{ $requests->links('pagination::bootstrap-4') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                </main>
                            </div>
                            <!-- /.row -->
                        </div><!-- /.container-fluid -->
                    </div>
                    <!-- /.content -->

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
