<?php

namespace Weave\BlockNote\Uploads;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Weave\BlockNote\Contracts\StoresBlockNoteUploads;

class FilesystemBlockNoteUpload implements StoresBlockNoteUploads
{
    public function store(UploadedFile $file): string
    {
        $disk = config('weave-blocknote.uploads.disk', 'public');
        $directory = trim((string) config('weave-blocknote.uploads.directory', 'blocknote'), '/');
        $visibility = (string) config('weave-blocknote.uploads.visibility', 'public');

        $path = Storage::disk($disk)->putFile($directory, $file, ['visibility' => $visibility]);
        if ($path === false) {
            throw new RuntimeException('Could not store uploaded file.');
        }

        return Storage::disk($disk)->url($path);
    }
}
