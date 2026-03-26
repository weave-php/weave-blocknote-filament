<?php

namespace Weave\BlockNote\Forms\Components;

use Filament\Forms\Components\Field;

class BlockNoteEditor extends Field
{
    protected string $view = 'weave-blocknote::forms.components.block-note-editor';

    protected string $minHeight = '320px';

    protected bool $hasFullscreenButton = false;

    public function fullscreenButton(bool $enabled = true): static
    {
        $this->hasFullscreenButton = $enabled;

        return $this;
    }

    public function getHasFullscreenButton(): bool
    {
        return $this->hasFullscreenButton;
    }

    public function minHeight(int|string $height): static
    {
        $this->minHeight = is_int($height) ? "{$height}px" : $height;

        return $this;
    }

    public function getMinHeight(): string
    {
        return $this->minHeight;
    }
}
