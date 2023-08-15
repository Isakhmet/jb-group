<?php

namespace App\Helpers;

use App\Models\MediaAlbum;
use App\Models\MediaFiles;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class MediaFilesHelper
{
    protected const DEFAULT_ALBUM = 'все фотки';

    public function saveFile(string $album, UploadedFile $file)
    {
        $newFiles = new MediaFiles();

        if (strcmp($album, self::DEFAULT_ALBUM) !== 0) {
            $file->move(public_path('images')."/$album", $file->getClientOriginalName());

            $newFiles->name = $file->getClientOriginalName();
            $newFiles->format = $file->getClientMimeType();
            $newFiles->album_id = MediaAlbum::where('name', $album)->first()->id;
            $newFiles->save();
        }else {
            $newFiles->name = $file->getClientOriginalName();
            $newFiles->format = $file->getClientMimeType();
            $newFiles->save();

            $file->move(public_path('images'), $newFiles->name);
        }
    }

    public function removeFile($file, $album)
    {
        if (strcmp($album, self::DEFAULT_ALBUM) !== 0) {
            if (File::exists(public_path("images/$album/$file"))){
                File::delete(public_path("images/$album/$file"));
            }

            $albumId = MediaAlbum::where('name', $album)->first()->id;

            MediaFiles::where('album_id', $albumId)
                      ->where('name', $file)
                      ->delete();
        }else {
            if (File::exists(public_path("images/$file"))){
                File::delete(public_path("images/$file"));
            }

            MediaFiles::where('name', $file)
                      ->delete();
        }
    }
}
