<?php

declare(strict_types=1);

namespace NPShop\BgCode\Reader;

use NPShop\BgCode\Block\Block;
use NPShop\BgCode\Block\BlockType;
use NPShop\BgCode\Block\FileMetadataBlock;
use NPShop\BgCode\Block\GCodeBlock;
use NPShop\BgCode\Block\PrinterMetadataBlock;
use NPShop\BgCode\Block\PrintMetadataBlock;
use NPShop\BgCode\Block\SlicerMetadataBlock;
use NPShop\BgCode\Block\ThumbnailBlock;
use NPShop\BgCode\Compression\CompressionType;
use NPShop\BgCode\Header\BlockHeader;
use NPShop\BgCode\Support\UInt;

final class BlockReader
{
    public function __construct(private readonly BinaryReader $reader, private readonly int $checksumType) {}

    public function next(): ?Block
    {
        $remaining = $this->reader->size() - $this->reader->position();
        if ($remaining === 0 || $remaining === 4) {
            return null;
        }
        $start = $this->reader->position();
        $type = BlockType::tryFrom($this->reader->u16());
        if ($type === null) {
            throw new \RuntimeException("Invalid block type at 0x" . dechex($start));
        }
        $compression = CompressionType::tryFrom($this->reader->u16());
        if ($compression === null) {
            throw new \RuntimeException("Invalid compression at 0x" . dechex($start));
        }
        $uncompressed = $this->reader->u32();
        $compressed = $compression === CompressionType::None ? $uncompressed : $this->reader->u32();
        $header = new BlockHeader($type, $compression, $uncompressed, $compressed, $start);
        $paramSize = $type === BlockType::Thumbnail ? 6 : 2;
        $parameters = $this->reader->read($paramSize);
        $payload = $this->reader->read($compressed);
        $checksum = $this->checksumType === 1 ? $this->reader->u32() : null;
        return match ($type) {
            BlockType::FileMetadata => new FileMetadataBlock($header, $parameters, $payload, $checksum),
            BlockType::GCode => new GCodeBlock($header, $parameters, $payload, $checksum),
            BlockType::SlicerMetadata => new SlicerMetadataBlock($header, $parameters, $payload, $checksum),
            BlockType::PrinterMetadata => new PrinterMetadataBlock($header, $parameters, $payload, $checksum),
            BlockType::PrintMetadata => new PrintMetadataBlock($header, $parameters, $payload, $checksum),
            BlockType::Thumbnail => new ThumbnailBlock($header, $parameters, $payload, $checksum),
        };
    }
}
