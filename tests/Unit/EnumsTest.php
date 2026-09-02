<?php

declare(strict_types=1);

namespace NickNickDevelopment\BgCode\Tests\Unit;

use NickNickDevelopment\BgCode\Block\BlockType;
use NickNickDevelopment\BgCode\Compression\CompressionType;
use PHPUnit\Framework\TestCase;

final class EnumsTest extends TestCase
{
    public function testBlockTypesMatchUpstreamNumbers(): void
    {
        self::assertSame(0, BlockType::FileMetadata->value);
        self::assertSame(1, BlockType::GCode->value);
        self::assertSame(5, BlockType::Thumbnail->value);
    }

    public function testCompressionTypesMatchUpstreamNumbers(): void
    {
        self::assertSame(0, CompressionType::None->value);
        self::assertSame(1, CompressionType::Deflate->value);
        self::assertSame(2, CompressionType::Heatshrink11_4->value);
        self::assertSame(3, CompressionType::Heatshrink12_4->value);
    }
}
