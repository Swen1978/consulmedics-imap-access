<?php

namespace Consulmedics\ImapAccess\Tests;

use PHPUnit\Framework\TestCase;

class ComposerConstraintTest extends TestCase
{
    public function test_laravel_framework_constraint_allows_laravel_12(): void
    {
        $composer = json_decode(
            file_get_contents(dirname(__DIR__) . '/composer.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $constraint = $composer['require']['laravel/framework'] ?? '';

        $this->assertNotSame('', $constraint);
        $this->assertMatchesRegularExpression('/\^12(\.0)?/', $constraint);
        $this->assertMatchesRegularExpression('/\^10(\.0)?/', $constraint);
        $this->assertMatchesRegularExpression('/\^11(\.0)?/', $constraint);
    }

    public function test_service_provider_source_is_present(): void
    {
        $path = dirname(__DIR__) . '/src/Providers/ImapAccessServiceProvider.php';

        $this->assertFileExists($path);
        $this->assertStringContainsString(
            'class ImapAccessServiceProvider',
            file_get_contents($path)
        );
    }
}
