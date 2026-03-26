<?php

namespace Weave\BlockNote;

use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Weave\BlockNote\Contracts\StoresBlockNoteUploads;
use Weave\BlockNote\Uploads\FilesystemBlockNoteUpload;

class BlockNoteServiceProvider extends PackageServiceProvider
{
    public static string $name = 'weave-blocknote';

    public function register(): void
    {
        parent::register();

        $this->app->singleton(StoresBlockNoteUploads::class, FilesystemBlockNoteUpload::class);
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasRoutes(['web'])
            ->hasViews('weave-blocknote')
            ->hasTranslations();
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            Css::make('blocknote-editor', __DIR__ . '/../dist/blocknote-editor.css'),
            Js::make('blocknote-editor', __DIR__ . '/../dist/blocknote-editor.js'),
        ], 'weave-php/blocknote-filament');
    }
}
