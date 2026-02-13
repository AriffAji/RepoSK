<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownloadLog extends Model
{
    protected $fillable = [
        'file_id',
        'ip_address',
        'user_agent',
        'downloaded_at'
    ];

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
