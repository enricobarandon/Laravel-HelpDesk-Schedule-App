<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;

    protected $table = 'archive';

    protected $fillable = [
        'id',
        'bet_count',
        'transaction_count',
        'date_covered',
        'fg_link',
        'schedule_link',
        'start',
        'end',
        'duration',
        'requested_by',
        'processed_by',
        'created_at',
        'updated_at',
    ];
}
