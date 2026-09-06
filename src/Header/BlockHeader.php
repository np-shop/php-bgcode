<?php

declare(strict_types=1);

namespace NPShop\BgCode\Header;

use NPShop\BgCode\Block\BlockType;
use NPShop\BgCode\Compression\CompressionType;

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
