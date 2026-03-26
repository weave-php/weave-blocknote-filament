<?php

namespace Weave\BlockNote\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Weave\BlockNote\BlockNoteServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            BlockNoteServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
    }
}
