<?php

use Illuminate\Support\Facades\Route;
use Weave\BlockNote\Http\Controllers\BlockNoteUploadController;

$middleware = config('weave-blocknote.uploads.middleware', ['web', 'auth']);
$throttle = config('weave-blocknote.uploads.throttle');

if (is_string($throttle) && $throttle !== '') {
    $middleware = array_merge($middleware, ['throttle:'.$throttle]);
}

Route::middleware($middleware)
    ->post('weave-blocknote/upload', BlockNoteUploadController::class)
    ->name('weave-blocknote.upload');
