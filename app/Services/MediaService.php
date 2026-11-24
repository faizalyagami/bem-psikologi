<?php

namespace App\Services;

use App\Models\MediaFile;
use App\Models\MediaFolder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaService
{
    /**
     * Upload satu atau banyak file ke folder.
     * $folderPath = "posts" atau "posts/2026"
     */
    public function uploadFiles(array $files, ?int $folderId = null): array
    {
        $uploaded = [];
        $folder = $folderId ? MediaFolder::find($folderId) : null;
        $basePath = $folder ? 'media/' . $folder->full_path : 'media';

        foreach ($files as $file) {
            if (!$file instanceof UploadedFile) continue;

            $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs($basePath, $filename, 'public');

            $media = MediaFile::create([
                'folder_id' => $folderId,
                'user_id' => Auth::id(),
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);

            $uploaded[] = $media;
        }

        return $uploaded;
    }

    public function deleteFile(MediaFile $media): void
    {
        if (Storage::disk('public')->exists($media->path)) {
            Storage::disk('public')->delete($media->path);
        }
        $media->delete();
    }

    public function moveFile(MediaFile $media, ?int $folderId): MediaFile
    {
        $folder = $folderId ? MediaFolder::find($folderId) : null;
        $newBase = $folder ? 'media/' . $folder->full_path : 'media';
        $newPath = $newBase . '/' . basename($media->path);

        if (Storage::disk('public')->exists($media->path)) {
            Storage::disk('public')->move($media->path, $newPath);
        }

        $media->update(['folder_id' => $folderId, 'path' => $newPath]);

        return $media;
    }

    public function createFolder(string $name, ?int $parentId = null): MediaFolder
    {
        return MediaFolder::create([
            'name' => $name,
            'parent_id' => $parentId,
        ]);
    }

    public function deleteFolder(MediaFolder $folder): void
    {
        // Hapus semua file di folder ini
        foreach ($folder->files as $file) {
            $this->deleteFile($file);
        }

        // Rekursif hapus subfolder
        foreach ($folder->children as $child) {
            $this->deleteFolder($child);
        }

        $folder->delete();
    }
}
