<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('news.index', ['events' => News::query()->orderBy('created_at', 'desc')->get()]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('news.create');
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
            'type' => 'required|string',
            'is_fixed' => 'string',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $news = new News();

        $news->title = $request->input('title');
        $news->message = $request->input('message');
        $news->type = $request->input('type');

        if ($request->has('is_fixed')) $news->is_fixed = true;

        if ($request->has('image')) {
            $image = $request->file('image');
            $news->image = Carbon::now() . '.' . $image->getClientOriginalName();
            $image->move(Storage::disk('public')->path('news/images'), $news->image);
        }

        if ($request->has('file_name')) {
            $file = $request->file('file_name');
            $news->file = Carbon::now() . '.' . $file->getClientOriginalName();
            $file->move(Storage::disk('public')->path('news/files'), $news->file);
        }

        $news->save();

        return redirect()->route('events.index', ['success' => 'Image uploaded successfully.']);
    }

    public function showNews()
    {
        return view('news.show', ['events' => News::all()]);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $event = News::query()->find($id);
        $data['event'] = $event;
        $data['types'] = ['Обычное', 'Срочное', 'Поздравление'];

        if ($event->image) $data['image'] = Str::substr($event->image, strpos($event->image, ".")+1);

        if ($event->file) $data['file'] = Str::substr($event->file, strpos($event->file, ".")+1);

        return view('news.edit', $data);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $news = News::query()->find($id);

        $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
            'type' => 'required|string',
            'is_fixed' => 'string',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $news->title = $request->input('title');
        $news->message = $request->input('message');
        $news->type = $request->input('type');
        $news->is_fixed = $request->has('is_fixed') ? true : false;

        if ($request->has('file_name')) {
            $file = $request->file('file_name');

            if (!isset($news->image) || $file->getClientOriginalName() !== Str::substr($news->file, strpos($news->file, ".")+1)) {
                $news->file = Carbon::now() . '.' . $file->getClientOriginalName();
                $file->move(Storage::disk('public')->path('news/files'), $news->file);

            }
        } else{
            if (isset($news->file) && empty($request->input('old_file'))) {
                if (File::exists(Storage::disk('public')->path('news/files/'.$news->file))){
                    File::delete(Storage::disk('public')->path('news/files/'.$news->file));
                }

                $news->file = null;
            }
        }

        if ($request->has('image')) {
            $image = $request->file('image');

            if (!isset($news->image) || $image->getClientOriginalName() !== Str::substr($news->image, strpos($news->image, ".")+1)) {
                $news->image = Carbon::now() . '.' . $image->getClientOriginalName();
                $image->move(Storage::disk('public')->path('news/images'), $news->image);
            }
        } else{
            if (isset($news->image) && empty($request->input('old_image'))) {
                if (File::exists(Storage::disk('public')->path('news/images/'.$news->image))){
                    File::delete(Storage::disk('public')->path('news/images/'.$news->image));
                }

                $news->image = null;
            }
        }

        $news->save();

        return redirect()->route('events.index', ['success' => 'Данные успешно обновлены.']);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        News::query()->find($id)->delete();

        return redirect()->back()->with('success', 'удалено');
    }
}
