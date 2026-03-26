<?php

namespace Weave\BlockNote\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

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
