<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledGroup extends Model
{
    use HasFactory;

    protected $table = 'scheduled_groups';

    protected $fillable = ['schedule_id', 'group_id', 'user_id'];
}
