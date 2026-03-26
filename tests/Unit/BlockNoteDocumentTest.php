<?php

namespace Weave\BlockNote\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Weave\BlockNote\Support\BlockNoteDocument;
use Weave\BlockNote\Tests\TestCase;

final class BlockNoteDocumentTest extends TestCase
{
    #[Test]
    public function it_extracts_plain_text_from_blocks(): void
    {
        $json = <<<'JSON'
[
  {
    "id": "a",
    "type": "paragraph",
    "props": {},
    "content": [{ "type": "text", "text": "Hello", "styles": {} }],
    "children": []
  }
]
JSON;

        $this->assertSame('Hello', BlockNoteDocument::toPlainText($json, 0));
    }

    #[Test]
    public function it_returns_empty_for_invalid_json(): void
    {
        $this->assertSame('', BlockNoteDocument::toPlainText('not-json'));
    }
}
