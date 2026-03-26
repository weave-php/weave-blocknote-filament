<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Built-in upload endpoint
    |--------------------------------------------------------------------------
    |
    | When enabled, the package registers POST /weave-blocknote/upload and the
    | BlockNote field uses it by default. Set to false to rely only on a custom
    | URL via BlockNoteEditor::uploadUrl() or to disable uploads entirely.
    |
    */
    'uploads' => [
        'enabled' => env('WEAVE_BLOCKNOTE_UPLOADS_ENABLED', true),

        /*
         * Always default to `public` so stored files are reachable at /storage/... after
         * `php artisan storage:link`. Do not inherit FILESYSTEM_DISK (often `local` / private).
         */
        'disk' => env('WEAVE_BLOCKNOTE_UPLOADS_DISK', 'public'),

        'directory' => env('WEAVE_BLOCKNOTE_UPLOADS_DIRECTORY', 'blocknote'),

        /*
         * Stored file visibility: "public" or "private" (S3, etc.).
         */
        'visibility' => env('WEAVE_BLOCKNOTE_UPLOADS_VISIBILITY', 'public'),

        /*
         * Max file size in kilobytes (Laravel "max" rule for uploaded files).
         */
        'max_size_kb' => (int) env('WEAVE_BLOCKNOTE_UPLOADS_MAX_KB', 12_288),

        /*
         * Middleware for the upload route (session + CSRF from "web").
         * Adjust if your Filament panel uses another guard (e.g. `auth:sanctum`).
         */
        'middleware' => ['web', 'auth'],

        /*
         * Laravel throttle string, e.g. `60,1` (60 requests per minute per user+IP).
         * Set to null to disable rate limiting on this route.
         */
        'throttle' => env('WEAVE_BLOCKNOTE_UPLOADS_THROTTLE', '60,1'),

        /*
         * Optional authorization callback: return false to respond with 403.
         * Example: fn (\Illuminate\Http\Request $request) => $request->user()?->can('upload', 'blocknote') ?? false
         */
        'authorize' => null,

        /*
         * JSON key returned to BlockNote (must match uploadResponseUrlKey() on the field if you change it).
         */
        'response_url_key' => 'url',

        /*
         * Form input name for the file (must match uploadFieldName() on the field).
         */
        'input_name' => 'file',
    ],

];
