<?php

namespace Weave\BlockNote\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

/**
 * Registers the package with a Filament panel. Assets are already registered globally via {@see \Weave\BlockNote\BlockNoteServiceProvider};
 * this plugin keeps an explicit hook for panel configuration and future panel-scoped options.
 */
final class BlockNotePlugin implements Plugin
{
    public static function make(): static
    {
        return new self;
    }

    public function getId(): string
    {
        return 'weave-blocknote';
    }

    public function register(Panel $panel): void {}

    public function boot(Panel $panel): void {}
}
