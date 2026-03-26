<?php

namespace Weave\BlockNote\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Weave\BlockNote\Tests\TestCase;

final class UploadRouteTest extends TestCase
{
    #[Test]
    public function upload_returns_404_when_disabled(): void
    {
        $this->withoutMiddleware([
            \Illuminate\Auth\Middleware\Authenticate::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        ]);

        config(['weave-blocknote.uploads.enabled' => false]);

        $this->postJson('/weave-blocknote/upload', [])->assertNotFound();
    }
}
