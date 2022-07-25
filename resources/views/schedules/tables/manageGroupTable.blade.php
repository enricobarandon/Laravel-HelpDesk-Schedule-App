<table class="table sm-global-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Full Name</th>
            <th>Contact</th>
            <th>Username</th>
            <th>Password</th>
            <th>Role</th>
            <th>Side</th>
            <th>Remarks</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>

        @php
            $accountCount = 1;
            $groupAccountsCount = count($groupAccounts);
        @endphp
        
        @foreach($groupAccounts as $account)
            @php
            $status = '';
            $statusCSS = '';
            if($account->scheduled_group_id){
                $status = 'Confirmed';
                $statusCSS = 'td-green';
            } else {
                $status = 'Unconfirmed';
                $statusCSS = '';
            }
            $remarksCSS = '';
            if($account->is_active == 0){
                $remarksCSS = 'background: #dc3545; color: white';
            }else{
                $remarksCSS = 'background: #007bff; color: white';
            }
            $trClass = '';
            if($account->status == 'temporarydeactivated' and $account->is_active == 0){
                $trClass = 'background: yellow;';
            }elseif($account->status == 'permanentdeactivated' and $account->is_active == 0){
                $trClass = 'background: red; color: white';
            }else{
                $trClass = '';
            }
            @endphp
            <tr>
                <td style="{{ $trClass }}">{{ $accountCount++ }}</td>
                <td style="{{ $trClass }}">{{ htmlspecialchars($account->first_name. ' ' .$account->last_name) }}</td>
                <td style="{{ $trClass }}">{{ $account->contact }}</td>
                <td style="{{ $trClass }}">{{ $account->username }}</td>
                    @if($account->scheduled_group_id)
                    <td style="{{ $trClass }}">{{ isset($account->account_password) ? $account->account_password : '--' }}</td>
                    <td style="{{ $trClass }}">{{ htmlspecialchars($account->account_position) }}</td>
                    <td style="{{ $trClass }}">{{ $account->account_allowed_sides }}</td>
                    @else
                    <td style="{{ $trClass }}">{{ isset($account->password) ? $account->password : '--' }}</td>
                    <td style="{{ $trClass }}">{{ $account->position }}</td>
                    <td style="{{ $trClass }}">{{ $account->allowed_sides }}</td>
                    @endif
                <td style="{{ $remarksCSS }}">{{ $account->is_active == 0 ? 'DEACTIVATED' : 'ACTIVE' }}</td>
                <td>{{ $status }}</td>
            </tr>
        @endforeach

    </tbody>
</table>