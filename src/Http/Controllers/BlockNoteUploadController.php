<?php

namespace Weave\BlockNote\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Weave\BlockNote\Contracts\StoresBlockNoteUploads;

class BlockNoteUploadController extends Controller
{
    public function __invoke(Request $request, StoresBlockNoteUploads $storage): JsonResponse
    {
        if (! config('weave-blocknote.uploads.enabled', true)) {
            abort(404);
        }

        $fieldName = config('weave-blocknote.uploads.input_name', 'file');
        $maxKb = (int) config('weave-blocknote.uploads.max_size_kb', 12_288);

        $request->validate([
            $fieldName => ['required', 'file', 'max:'.$maxKb],
        ]);

        $file = $request->file($fieldName);
        if ($file === null) {
            abort(422);
        }

        $url = $storage->store($file);

        $key = config('weave-blocknote.uploads.response_url_key', 'url');

        return response()->json([$key => $url]);
    }
}
