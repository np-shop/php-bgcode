<?php

declare(strict_types=1);

namespace NickNickDevelopment\BgCode\Header;

use NickNickDevelopment\BgCode\Block\BlockType;
use NickNickDevelopment\BgCode\Compression\CompressionType;

final readonly class BlockHeader
{
    public function __construct(
        public BlockType $type,
        public CompressionType $compression,
        public int $uncompressedSize,
        public int $compressedSize,
        public int $position,
    ) {}

    public function headerSize(): int
    {
        return $this->compression === CompressionType::None ? 12 : 16;
    }
}
