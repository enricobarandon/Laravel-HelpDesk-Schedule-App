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

        $accounts = ScheduledAccount::select('account_id','accounts.group_id','first_name','last_name','username','position','allowed_sides','contact','remarks','password','accounts.status','accounts.is_active')
                        ->join('accounts','accounts.id','scheduled_accounts.account_id')
                        ->where('scheduled_accounts.schedule_id', $scheduleId)
                        ->get()
                        ->toArray();

        return collect($accounts)->groupBy('group_id')->all();
    }

}
