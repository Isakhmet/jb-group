<?php

namespace App\Http\Controllers;

use App\Helpers\MediaFilesHelper;
use App\Models\MediaAlbum;
use App\Models\MediaFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    protected const DEFAULT_ALBUM = 'все фотки';

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('media.index', ['files' => array_merge([['name' => 'все фотки']], MediaAlbum::all(['name'])->toArray())]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('media.create', ['files' => MediaAlbum::all(['name'])->toArray()]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if ($request->get('album')) {
            (new MediaFilesHelper())->saveFile($request->get('album'), $request->file('file'));
        }

        return response()->json();
    }

    public function show(Request $request)
    {
        $file = $request->get('album');

        if (strcmp($file, self::DEFAULT_ALBUM) === 0) {
            $files = MediaFiles::all();
        }else {
            if (!File::exists(public_path().'/images/'.$file)){
                return redirect()->back()->withErrors('Такой альбом не существует');
            }

            $files = MediaAlbum::with('images')->where('name', $file)->first()->images;
        }

        return view('media.show', ['album' => $file, 'images' => $files]);
    }


    public function edit($file)
    {
        if (strcmp($file, self::DEFAULT_ALBUM) === 0) {
            $files = MediaFiles::all();
        }else {
            if (!File::exists(public_path().'/images/'.$file)){
                return redirect()->back()->withErrors('Такой альбом не существует');
            }

            $files = MediaAlbum::with('images')->where('name', $file)->first()->images;
        }

        return view('media.add', ['album' => $file, 'images' => $files]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function createAlbum(Request $request)
    {
        $album = new MediaAlbum();
        $album->name = $request->get('name');
        $album->slug = Str::slug($request->get('name'), '-');
        $album->save();

        if (!File::exists(public_path('images/'.$request->get('name')))) {
            File::makeDirectory(public_path('images/'.$request->get('name')), '0777', true);
            exec('chmod -R 777 '. public_path('images/'.$request->get('name')));
        }

        return response()->json(['success' => true]);
    }

    public function removeAlbum(Request $request)
    {
        MediaAlbum::where('name', $request->get('name'))->first()->deleteImages();

        if (File::exists(public_path('images/'.$request->get('name')))) {
            File::deleteDirectory(public_path('images/'.$request->get('name')));
        }

        return response()->json(['success' => true]);
    }

    public function destroy($file, Request $request)
    {
        (new MediaFilesHelper())->removeFile($file, $request->get('album'));

        return response()->json(['success' => true]);
    }

    public function deleteByOne(Request $request)
    {
        foreach($request->get('images') as $image) {
            (new MediaFilesHelper())->removeFile($image, $request->get('album'));
        }

        return redirect()->back();
    }
}
