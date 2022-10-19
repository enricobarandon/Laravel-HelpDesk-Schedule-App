<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class ScheduledAccount extends Model
{
    use HasFactory;

    protected $table = 'scheduled_accounts';

    protected $fillable = [
        'scheduled_group_id',
        'group_id',
        'account_id'
    ];

    public static function createAccountsAssocArr($scheduleId) {

        $accounts = ScheduledAccount::select('account_id','accounts.group_id','first_name','last_name','username','account_position','account_allowed_sides','contact','remarks','account_password','accounts.status','accounts.is_active')
                        ->join('accounts','accounts.id','scheduled_accounts.account_id')
                        ->where('scheduled_accounts.schedule_id', $scheduleId)
                        ->orderby(DB::raw('case 
                                        when accounts.position = "Cashier" then 1 
                                        when accounts.position = "Teller" then 2
                                        when accounts.position = "Teller/Cashier" then 3
                                        when accounts.position = "Supervisor" then 4
                                        when accounts.position = "Operator" then 5
                                        end'))
                        ->get()
                        ->toArray();

        return collect($accounts)->groupBy('group_id')->all();
    }

    public static function updateUserCurEvent($scheduleId, $accountId, $data) {
        $update = ScheduledAccount::where('schedule_id', $scheduleId)
                            ->where('account_id', $accountId)
                            ->update([
                                'account_allowed_sides' => $data['allowed_sides'],
                                'account_position'  => $data['position']
                            ]);
    }
}
