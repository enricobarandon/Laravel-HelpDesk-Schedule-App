@extends('layouts.app')

@section('content')
@section('title','Dashboard')

@php
  $users = Auth::user();
@endphp

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            <div class="card card-info">
                <div class="card-header"><h5>Groups Record</h5></div>

                <div class="card-body">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>{{ Number_format($activeGroups) }}</h3>
                                    <p>Active Groups</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-object-group"></i>
                                </div>
                                <a href="{{ url('/groups/view/active') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <h3>{{ Number_format($deactivatedGroups + $deactivatedGroupsNull) }}</h3>
                                    <p>Deactivated Groups</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-object-group"></i>
                                </div>
                                <a href="{{ url('/groups/view/deactivated') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="small-box bg-gray">
                                <div class="inner">
                                    <h3>{{ Number_format($fulloutGroups) }}</h3>
                                    <p>Pullout Groups</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-object-group"></i>
                                </div>
                                <a href="{{ url('/groups/view/pullout') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="small-box bg-white">
                                <div class="inner">
                                    <h3>{{ Number_format($allGroups) }}</h3>
                                    <p>Total Groups</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-object-group"></i>
                                </div>
                                <a class="small-box-footer"><i class="fa fa-info-circle"></i></a>
                            </div>
                        </div>

                    </div>

                </div>
            </div>


            @if(in_array($users->user_type_id, [1,2,3]))
            <div class="card card-info">
                <div class="card-header"><h5>Accounts Record (Teller, Cashier, Teller/Cashier, Supervisor and Operator)</h5></div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-3">
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>{{ Number_format($activeAccounts) }}</h3>
                                    <p>Active Accounts</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-list"></i>
                                </div>
                                <a href="{{ url('/accounts') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <h3>{{ Number_format($deactivatedAccounts) }}</h3>
                                    <p>Deactivated Accounts</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-list"></i>
                                </div>
                                <a href="{{ url('/accounts/deactivated') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="small-box bg-white">
                                <div class="inner">
                                    <h3>{{ Number_format($allAccounts) }}</h3>
                                    <p>Total Accounts</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-list"></i>
                                </div>
                                <a class="small-box-footer"><i class="fa fa-info-circle"></i></a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @endif

            @if(in_array($users->user_type_id, [1,2,3,4]))
            <div class="card card-info">
                <div class="card-header"><h5>Request Summary</h5></div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-3">
                            <div class="small-box bg-blue">
                                <div class="inner">
                                    <h3>{{ Number_format($pendingAccount) }}</h3>
                                    <p>Pending</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-users"></i>
                                </div>
                                <a href="{{ url('/requests?keyword=&status=pending') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>{{ Number_format($approvedAccount) }}</h3>
                                    <p>Approved</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-users"></i>
                                </div>
                                <a href="{{ url('/requests?keyword=&status=approved') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <h3>{{ Number_format($rejectedAccount) }}</h3>
                                    <p>Rejected</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-users"></i>
                                </div>
                                <a href="{{ url('/requests?keyword=&status=rejected') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
@section('style')
    <link rel="stylesheet" href="https://dev-kyc.tripledg.co/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endsection

