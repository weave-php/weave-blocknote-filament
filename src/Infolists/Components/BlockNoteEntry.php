<?php

namespace Weave\BlockNote\Infolists\Components;

use Filament\Infolists\Components\TextEntry;
use Weave\BlockNote\Support\BlockNoteDocument;

/**
 * Read-only infolist entry: plain-text preview of BlockNote JSON.
 */
class BlockNoteEntry extends TextEntry
{
    public static function make(?string $name = null): static
    {
        $entry = parent::make($name);

        return $entry->formatStateUsing(
            fn (?string $state): string => BlockNoteDocument::toPlainText($state)
        );
    }
}
