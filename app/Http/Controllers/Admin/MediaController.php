<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaFile;
use App\Models\MediaFolder;
use App\Services\MediaService;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    protected MediaService $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->middleware('permission:manage-media');
        $this->mediaService = $mediaService;
    }

    public function index(Request $request)
    {
        $folderId = $request->get('folder');
        $folders = MediaFolder::whereNull('parent_id')->orderBy('name')->get();
        $subFolders = $folderId ? MediaFolder::where('parent_id', $folderId)->orderBy('name')->get() : collect();

        $query = MediaFile::with('folder')->latest();

        if ($folderId) {
            $query->where('folder_id', $folderId);
        } else {
            $query->whereNull('folder_id');
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('name', 'like', "%$s%");
        }

        $files = $query->paginate(24)->withQueryString();
        $activeFolder = $folderId ? MediaFolder::find($folderId) : null;

        // Breadcrumb folder
        $breadcrumbs = [];
        if ($activeFolder) {
            $f = $activeFolder;
            while ($f) {
                array_unshift($breadcrumbs, $f);
                $f = $f->parent;
            }
        }

        return view('admin.media.index', compact('folders', 'subFolders', 'files', 'activeFolder', 'breadcrumbs'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['file', 'max:10240'], // max 10MB per file
            'folder_id' => ['nullable', 'exists:media_folders,id'],
        ]);

        $uploaded = $this->mediaService->uploadFiles(
            $request->file('files'),
            $request->input('folder_id')
        );

        ActivityLogger::log('media.uploaded', null, ['count' => count($uploaded)]);

        return back()->with('success', count($uploaded) . ' file berhasil diunggah.');
    }

    public function createFolder(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'parent_id' => ['nullable', 'exists:media_folders,id'],
        ]);

        $folder = $this->mediaService->createFolder($request->name, $request->parent_id);
        ActivityLogger::log('media.folder_created', $folder, ['name' => $folder->name]);

        return back()->with('success', "Folder {$folder->name} berhasil dibuat.");
    }

    public function deleteFolder(MediaFolder $folder)
    {
        $name = $folder->name;
        $this->mediaService->deleteFolder($folder);
        ActivityLogger::log('media.folder_deleted', null, ['name' => $name]);

        return redirect()->route('admin.media.index')->with('success', "Folder {$name} berhasil dihapus.");
    }

    public function destroy(MediaFile $media)
    {
        $name = $media->name;
        $this->mediaService->deleteFile($media);
        ActivityLogger::log('media.deleted', null, ['name' => $name]);

        return back()->with('success', "File {$name} berhasil dihapus.");
    }

    public function move(Request $request, MediaFile $media)
    {
        $request->validate(['folder_id' => ['nullable', 'exists:media_folders,id']]);
        $this->mediaService->moveFile($media, $request->folder_id);

        return back()->with('success', 'File berhasil dipindahkan.');
    }
}
