<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceStat extends Model
{
    protected $fillable = [
        'disk_total',
        'disk_used',
        'cpu_usage',
        'ram_used'
    ];
}
