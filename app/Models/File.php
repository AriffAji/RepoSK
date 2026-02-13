<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'folder_id',
        'filename',
        'stored_name',
        'extension',
        'size',
        'path',
        'is_public'
    ];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}
