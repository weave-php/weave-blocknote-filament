<?php

namespace Weave\BlockNote;

use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BlockNoteServiceProvider extends PackageServiceProvider
{
    public static string $name = 'weave-blocknote';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasViews('weave-blocknote')
            ->hasTranslations();
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            Css::make('blocknote-editor', __DIR__ . '/../dist/blocknote-editor.css'),
            Js::make('blocknote-editor', __DIR__ . '/../dist/blocknote-editor.js'),
        ], 'weave-php/blocknote');
    }
}
