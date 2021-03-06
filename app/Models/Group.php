<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';

    protected $fillable = [
        'uuid',
        'province_id',
        'is_active',
        'group_type',
        'name',
        'code',
        'owner',
        'contact',
        'address',
        'installed_pc',
        'active_staff',
        'guarantor',
        'status',
        'operation_date',
        'pullout_date'
    ];
    
}
