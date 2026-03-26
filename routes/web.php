<?php

use Illuminate\Support\Facades\Route;
use Weave\BlockNote\Http\Controllers\BlockNoteUploadController;

Route::middleware(config('weave-blocknote.uploads.middleware', ['web', 'auth']))
    ->post('weave-blocknote/upload', BlockNoteUploadController::class)
    ->name('weave-blocknote.upload');
