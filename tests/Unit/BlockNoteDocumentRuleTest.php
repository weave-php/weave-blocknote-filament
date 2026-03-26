<?php

namespace Weave\BlockNote\Tests\Unit;

use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\Test;
use Weave\BlockNote\Rules\BlockNoteDocumentRule;
use Weave\BlockNote\Tests\TestCase;

final class BlockNoteDocumentRuleTest extends TestCase
{
    #[Test]
    public function it_passes_for_valid_blocknote_json(): void
    {
        $json = '[{"id":"a","type":"paragraph","props":{},"content":[],"children":[]}]';

        $v = Validator::make(
            ['body' => $json],
            ['body' => [new BlockNoteDocumentRule]],
        );

        $this->assertTrue($v->passes());
    }

    #[Test]
    public function it_fails_for_empty_array_and_uses_translation_key(): void
    {
        app()->setLocale('fr');

        $v = Validator::make(
            ['body' => '[]'],
            ['body' => [new BlockNoteDocumentRule]],
        );

        $this->assertFalse($v->passes());
        $this->assertStringContainsString('contenir', $v->errors()->first('body') ?? '');
    }
}
