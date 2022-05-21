@extends('layouts.app')

@section('content')
<!-- Main content -->
<div class="content">
      <div class="container-fluid">
        <div class="row">
            <main class="py-4">

              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title"><i class="fa fa-cog"></i> Group Management</h3>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Group Name</label>
                        <textarea id="gName" name="gName" class="form-control" id="gName" rows="2" disabled>{{ $groupInfo->group_name }}</textarea>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Address</label>
                        <textarea id="gAddress" name="gAddress" class="form-control" id="gAddress" rows="2" disabled>{{ $groupInfo->address }}</textarea>
                      </div>
                    </div>


                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Group Type</label>
                        <input type="text" value="{{ $groupInfo->group_type }}" class="form-control" id="gType" disabled>
                      </div>
                      <div class="form-group">
                        <label>Group Code</label>
                        <input type="text" value="{{ $groupInfo->code }}" class="form-control" id="gCode" disabled>
                      </div>
                      <div class="form-group">
                        <label>No. of PC Installed</label>
                        <input type="text" value="{{ $groupInfo->installed_pc }}" class="form-control" id="gNoPC" disabled>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Operator</label>
                        <input type="text" value="{{ $groupInfo->owner }}" class="form-control" id="gOperator" disabled>
                      </div>
                      <div class="form-group">
                        <label>Site</label>
                        <input type="text" value="{{ $groupInfo->site }}" class="form-control" id="gSite" disabled>
                      </div>
                      <div class="form-group">
                        <label>No. of Staff</label>
                        <input type="text" value="{{ $groupInfo->active_staff }}" class="form-control" id="gNoStaff" disabled>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Contact</label>
                        <input type="text" value="{{ $groupInfo->contact }}" class="form-control" id="gContact" disabled>
                      </div>
                      <div class="form-group">
                        <label>Guarantor</label>
                        <input type="text" value="--" class="form-control" id="gGuarantor" disabled>
                      </div>
                      <div class="form-group p-relative">
                        <form action='{{ url("/schedules/$scheduleId/groups/$groupId") }}' method="POST">
                            @csrf
                            @method('put')
                            <label>Time of Operation</label>
                            <input type="text" value="{{ date('h:i A', strtotime($scheduledGroupInfo->operation_time)) }}" class="form-control" id="operation_time" name="operation_time">
                            <button type="submit" class="btn btn-primary pull-right" id="updateGroupTime"><i class="fa fa-wrench"aria-hidden="true"></i> Update</button>
                        </form>
                      </div>
                    </div>

                    <table class="table global-table">
                      <thead>
                        <tr>
                          <th>#</td>
                          <th>Full Name</td>
                          <th>Contact</td>
                          <th>Role</td>
                          <th>Username</td>
                          <th>Password</td>
                          <th>Status</td>
                          <th>Action</td>
                        </tr>
                      </thead>
                      <tbody>

                        @php
                          $accountCount = 1;
                        @endphp
                        
                        @foreach($groupAccounts as $account)
                          @php
                            $status = '';
                            if($account->scheduled_group_id){
                              $status = 'Confirmed';
                            } else {
                              $status = 'Unconfirmed';
                            }
                          @endphp
                          <tr>
                            <td>
                              {{ $accountCount++ }}
                            </td>
                            <td>
                              <input type="text" value="{{ $account->first_name }} {{ $account->last_name }}" class="form-control" disabled>
                            </td>
                            <td>
                              <input type="text" value="{{ $account->contact }}" class="form-control" disabled>
                            </td>
                            <td>
                              <input type="text" value="{{ $account->position }}" class="form-control" disabled>
                            </td>
                            <td>
                              <input type="text" value="{{ $account->username }}" class="form-control" disabled>
                            </td>
                            <td>
                              <input type="text" value="--" class="form-control" disabled>
                            </td>
                            <td>
                              <input type="text" value="{{ $status }}" class="form-control" disabled>
                            </td>
                            <td>
                              @if($account->scheduled_group_id)
                                <form action='{{ url("/scheduledaccount/$scheduledGroupInfo->id/account/$account->acc_id") }}' method="POST">
                                  @csrf
                                  @method('delete')
                                  <button class="btn btn-danger"><i class="fa fa-times"></i> Remove</button>
                                </form>
                              @else
                                <form action='{{ url("/scheduledaccount/$scheduledGroupInfo->id/account/$account->acc_id") }}' method="POST">
                                  @csrf
                                  @method('post')
                                  <input type="hidden" name="groupId" value="{{ $groupId }}">
                                  <input type="hidden" name="scheduleId" value="{{ $scheduleId }}">
                                  <button class="btn btn-success"><i class="fa fa-plus"></i> Confirm</button>
                                </form>
                              @endif
                              <!-- <button class="btn btn-primary"><i class="fa fa-wrench"></i> Edit</button> -->
                            </td>
                          </tr>
                        @endforeach

                      </tbody>
                    </table>

                    <!-- <div class="col-md-12 display-center">
                      <button type="button" class="btn btn-success"><i class="fas fa-plus"></i> Add Account</button>
                    </div> -->
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
  <!-- /.content-wrapper -->

  @endsection