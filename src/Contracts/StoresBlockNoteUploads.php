<?php

namespace Weave\BlockNote\Contracts;

use Illuminate\Http\UploadedFile;

interface StoresBlockNoteUploads
{
    public function store(UploadedFile $file): string;
}
