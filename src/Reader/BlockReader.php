<?php

declare(strict_types=1);

namespace NickNickDevelopment\BgCode\Reader;

use NickNickDevelopment\BgCode\Block\Block;
use NickNickDevelopment\BgCode\Block\BlockType;
use NickNickDevelopment\BgCode\Block\FileMetadataBlock;
use NickNickDevelopment\BgCode\Block\GCodeBlock;
use NickNickDevelopment\BgCode\Block\PrinterMetadataBlock;
use NickNickDevelopment\BgCode\Block\PrintMetadataBlock;
use NickNickDevelopment\BgCode\Block\SlicerMetadataBlock;
use NickNickDevelopment\BgCode\Block\ThumbnailBlock;
use NickNickDevelopment\BgCode\Compression\CompressionType;
use NickNickDevelopment\BgCode\Header\BlockHeader;
use NickNickDevelopment\BgCode\Support\UInt;

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
