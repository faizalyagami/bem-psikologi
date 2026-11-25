<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\MediaFile;
use Illuminate\Support\Facades\DB;

class GalleryController extends Controller
{
    public function index()
    {
        $images = MediaFile::query()
            ->where(function ($q) {
                $q->where('mime_type', 'like', 'image/%')
                    ->orWhereRaw('LOWER(path) REGEXP ?', ['\.(jpg|jpeg|png|webp|gif)$']);
            })
            ->latest()
            ->paginate(24);

        return view('frontend.gallery.index', compact('images'));
    }
}
