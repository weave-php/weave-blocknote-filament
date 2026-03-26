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

All standard Filament `Field` APIs apply (`label()`, `required()`, `disabled()`, `columnSpanFull()`, `live()`, etc.).

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
