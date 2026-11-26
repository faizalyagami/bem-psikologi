<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use App\Support\ActivityLogger;
use App\Support\ImageUploader;
use Illuminate\Http\Request;

class SiteContentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-settings');
    }

    public function index()
    {
        $contents = SiteContent::orderBy('group')->orderBy('label')->get()->groupBy('group');

        return view('admin.content.site-contents.index', compact('contents'));
    }

    public function update(Request $request)
    {
        $contents = SiteContent::all();

        foreach ($contents as $content) {
            if ($content->type === 'image') {
                if ($request->hasFile($content->key)) {
                    $old = $content->value;
                    $path = ImageUploader::upload($request->file($content->key), 'site-contents', $old);
                    $content->update(['value' => $path]);
                }
            } else {
                if ($request->has($content->key)) {
                    $content->update(['value' => $request->input($content->key)]);
                }
            }
        }

        ActivityLogger::log('site_contents.updated');

        return back()->with('success', 'Konten situs berhasil diperbarui.');
    }
}
