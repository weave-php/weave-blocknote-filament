<?php

namespace Weave\BlockNote\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Weave\BlockNote\Forms\Components\BlockNoteEditor;
use Weave\BlockNote\Tests\TestCase;

final class BlockNoteEditorLocaleTest extends TestCase
{
    #[Test]
    public function it_respects_locale_override(): void
    {
        $field = BlockNoteEditor::make('body')->blockNoteLocale('fr');

        $this->assertSame('fr', $field->getResolvedLocale());
    }

    #[Test]
    public function it_follows_app_locale_when_no_override(): void
    {
        app()->setLocale('de');

        $field = BlockNoteEditor::make('body');

        $this->assertSame('de', $field->getResolvedLocale());
    }
}
