<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaFiles extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'format',
        'album_id'
    ];

    public function album()
    {
        return $this->belongsTo(MediaAlbum::class, 'album_id', 'id');
    }
}
