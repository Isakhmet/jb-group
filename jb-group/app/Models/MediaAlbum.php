<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaAlbum extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug'
    ];

    public function images()
    {
        return $this->hasMany(MediaFiles::class, 'album_id', 'id');
    }

    public function deleteImages()
    {
        $this->images()->delete();
        $this->delete();
    }
}
