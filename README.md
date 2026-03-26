# weave-php/blocknote

A [BlockNote](https://www.blocknotejs.org/) rich-text editor as a **Filament form field** for Laravel. The editor runs in a bundled **React** app (BlockNote’s Mantine UI), persisted as **JSON** compatible with BlockNote’s document format.

## Requirements

- PHP **8.3+**
- Laravel **11+** (Illuminate components as required by your Filament version)
- **Filament** v3, v4, or v5 (`filament/forms`)

## Installation

```bash
composer require weave-php/blocknote
```

Laravel will auto-discover `Weave\BlockNote\BlockNoteServiceProvider`.

The provider follows Filament’s [standalone plugin](https://filamentphp.com/docs/plugins/building-a-standalone-plugin) conventions: it extends [**Spatie Laravel Package Tools**](https://github.com/spatie/laravel-package-tools) `PackageServiceProvider`, registers views and translations in `configurePackage()`, and registers CSS/JS with `FilamentAsset::register()` inside `packageBooted()` (as recommended in Filament’s [asset management](https://filamentphp.com/docs/advanced/assets) docs). Built files live under `vendor/weave-php/blocknote/dist/`.

### Publish assets (`php artisan filament:assets`)

Filament copies every registered asset (including this package’s) from `vendor/` into `public/`. Run it after installing or upgrading the package (or when Filament tells you to):

```bash
php artisan filament:assets
```

That publishes `blocknote-editor.js` and `blocknote-editor.css` to `public/js/weave-php/blocknote/` and `public/css/weave-php/blocknote/`, same mechanism as Filament’s own scripts and styles.

### Fonts

The field loads **Inter** from Filament’s published font bundle (`public/fonts/filament/filament/inter/`), which Filament already ships when you run `filament:assets` (or `filament:install`). Ensure that path exists so the editor typography matches the panel.

## Usage

Use `BlockNoteEditor` like any Filament field. The state is a **JSON string** representing a BlockNote document (array of blocks).

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Weave\BlockNote\Forms\Components\BlockNoteEditor;

public static function form(Schema $schema): Schema
{
    return $schema->components([
        TextInput::make('title')->required(),
        BlockNoteEditor::make('contents')
            ->label('Content')
            ->default('[]')
            ->minHeight(480)
            ->fullscreenButton()
            ->columnSpanFull(),
    ]);
}
```

Store the column as `longText` or `json` in your migration, depending on how you prefer to persist the payload.

### Field options

| Method | Description |
|--------|-------------|
| `minHeight(int\|string $height)` | Minimum height of the editor area. Integers are treated as **pixels** (e.g. `480` → `480px`). Any CSS length is allowed (`'50vh'`, `'24rem'`, …). Default: `320px`. |
| `fullscreenButton(bool $enabled = true)` | Shows a fullscreen toggle on the field. Default: **disabled**; call `fullscreenButton()` to enable. |
| `locale(?string $locale)` | Optional override for the BlockNote UI dictionary (e.g. `fr`, `en`, `zh-tw`). If omitted, **`app()->getLocale()`** is used (Laravel-style codes like `fr_FR` are normalized). |
| `disableUpload(bool $disabled = true)` | Turns off uploads (BlockNote only shows embed). Default: **uploads on**. |
| `uploadUrl(?string $url)` | Overrides the default upload endpoint (see below). By default the package registers **`POST /weave-blocknote/upload`** (`weave-blocknote.upload`). |
| `uploadFieldName(string $name = 'file')` | Form field name for the uploaded file (must match `uploads.input_name` in config if you change it). |
| `uploadResponseUrlKey(string $key = 'url')` | JSON key for the public URL in the upload response (must match `uploads.response_url_key` in config if you change it). |
| `blocks(array $types)` | **Whitelist**: only these BlockNote block types (see `BlockNoteEditor::BLOCK_TYPES`). `paragraph` is added if missing. |
| `withoutBlocks(array $types)` | **Blacklist**: remove these types from the default set. Ignored if `blocks()` was used. `paragraph` cannot be removed. |

All standard Filament `Field` APIs apply (`label()`, `required()`, `disabled()`, `columnSpanFull()`, `live()`, etc.).

### File uploads (default)

Uploads are **enabled by default**: the package registers **`POST /weave-blocknote/upload`** (name: `weave-blocknote.upload`) and stores files via `Weave\BlockNote\Contracts\StoresBlockNoteUploads` on the **`public` disk by default** (not your app’s `FILESYSTEM_DISK`, so image URLs stay under `/storage/...`). Run **`php artisan storage:link`** once so `public/storage` points at `storage/app/public`.

Publish and edit config:

```bash
php artisan vendor:publish --tag=weave-blocknote-config
```

Key options: `uploads.disk` (default `public`; set `WEAVE_BLOCKNOTE_UPLOADS_DISK` only if you know what you’re doing), `uploads.directory`, `uploads.visibility`, `uploads.middleware`, `uploads.max_size_kb`, `uploads.enabled`.

Override only the URL (e.g. custom controller):

```php
BlockNoteEditor::make('body')->uploadUrl(route('my.upload'));
```

Disable uploads entirely:

```php
BlockNoteEditor::make('body')->disableUpload();
```

### Custom storage (S3, CDN, etc.)

Bind your own implementation; return a **public URL** string for BlockNote:

```php
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Weave\BlockNote\Contracts\StoresBlockNoteUploads;

class MyBlockNoteUploads implements StoresBlockNoteUploads
{
    public function store(UploadedFile $file): string
    {
        $path = Storage::disk('s3')->putFile('blocknote', $file, 'public');

        return Storage::disk('s3')->url($path);
    }
}

// AppServiceProvider::register()
$this->app->singleton(
    \Weave\BlockNote\Contracts\StoresBlockNoteUploads::class,
    \App\BlockNote\MyBlockNoteUploads::class,
);
```

The bundled script sends **`Accept: application/json`**, **`credentials: 'same-origin'`**, and Laravel **CSRF** when present.

### Block types (enable / disable)

Default is **all** built-in BlockNote blocks (`BlockNoteEditor::BLOCK_TYPES`). Restrict embeds or heavy blocks:

```php
use Weave\BlockNote\Forms\Components\BlockNoteEditor;

BlockNoteEditor::make('body')
    ->withoutBlocks(['table', 'codeBlock', 'image', 'video', 'audio', 'file']);

BlockNoteEditor::make('minimal')
    ->blocks(['paragraph', 'heading', 'bulletListItem', 'numberedListItem']);
```

### Localization

BlockNote UI strings follow **`locale()`** when set, otherwise **`app()->getLocale()`**:

```php
BlockNoteEditor::make('body')->columnSpanFull();
```

Force a specific BlockNote language regardless of the app:

```php
BlockNoteEditor::make('body')->locale('fr');
```

Supported codes follow [BlockNote’s locale list](https://www.blocknotejs.org/docs/features/localization) (`ar`, `zh`, `zh-tw`, `en`, `fr`, …). Unknown codes fall back to English in the bundled script.

### Example: disable fullscreen

```php
BlockNoteEditor::make('body')
    ->minHeight('400px')
    ->fullscreenButton(false);
```

## Data format

The field value is a **string** containing JSON: a **non-empty array of BlockNote blocks**. Empty documents are typically `'[]'` or the default document BlockNote creates on first load.

Validate or cast in your model as needed, for example:

```php
protected function casts(): array
{
    return [
        'contents' => 'array', // if you decode JSON to array in PHP
    ];
}
```

If you keep it as a JSON string in the database, cast to `string` and decode when rendering outside Filament.

## Translations

English and French live under the `weave-blocknote` namespace (e.g. `weave-blocknote::editor.enter_fullscreen`). They are loaded from the package automatically.

To override strings, add keys under `lang/vendor/weave-blocknote/{locale}/` in your app, or register your own paths with `loadTranslationsFrom()` in a service provider.

## Development (this package)

To work on the JavaScript/CSS bundle:

```bash
cd vendor/weave-php/blocknote   # or clone path
npm install
npm run build
```

This runs `build.mjs` (esbuild) and outputs `dist/blocknote-editor.js` and `dist/blocknote-editor.css`. Commit `dist/` if you ship prebuilt assets, then run `php artisan filament:assets` in consuming apps.

**Stack:** React 19, `@blocknote/mantine`, esbuild. The Mantine BlockNote build is self-contained (no Tailwind setup required in the host app).

## License

MIT.
