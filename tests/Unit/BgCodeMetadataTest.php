<?php

declare(strict_types=1);

namespace NickNickDevelopment\BgCode\Tests\Unit;

use NickNickDevelopment\BgCode\BgCode;
use NickNickDevelopment\BgCode\Block\FileMetadataBlock;
use PHPUnit\Framework\TestCase;

final class BgCodeMetadataTest extends TestCase
{
    public function testItReadsFileMetadataFromFixture(): void
    {
        $bgcode = BgCode::open(__DIR__ . '/../Fixtures/test.bgcode');
        $metadata = $bgcode->metadata();

        self::assertArrayHasKey(FileMetadataBlock::class, $metadata);
        self::assertSame('PrusaSlicer 2.9.5', $metadata[FileMetadataBlock::class]['Producer']);
        self::assertSame('2026-09-01 at 18:33:30 UTC', $metadata[FileMetadataBlock::class]['Produced on']);
    }

    public function testItReadsGcodeFromFixture(): void
    {
        $bgcode = BgCode::open(__DIR__ . '/../Fixtures/test.bgcode');
        $gcode = iterator_to_array($bgcode->gcode(), false);

        self::assertNotEmpty($gcode);
        self::assertStringContainsString('G1', $gcode[0]);
    }

    public function testItReadsHeaderFromFixture(): void
    {
        $bgcode = BgCode::open(__DIR__ . '/../Fixtures/test.bgcode');
        $header = $bgcode->header();

        self::assertSame('GCDE', $header->magicString());
        self::assertSame(1, $header->version);
        self::assertSame(1, $header->checksumType);
    }
}
