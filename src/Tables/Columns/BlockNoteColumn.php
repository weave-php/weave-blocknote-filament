<?php

namespace Weave\BlockNote\Tables\Columns;

use Filament\Tables\Columns\TextColumn;
use Weave\BlockNote\Support\BlockNoteDocument;

class BlockNoteColumn extends TextColumn
{
    public static function make(?string $name = null): static
    {
        $column = parent::make($name);

        return $column->formatStateUsing(
            fn (?string $state): string => BlockNoteDocument::toPlainText($state)
        );
    }
}
