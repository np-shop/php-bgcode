<?php

declare(strict_types=1);

namespace NPShop\BgCode;

use NPShop\BgCode\Block\Block;
use NPShop\BgCode\Block\FileMetadataBlock;
use NPShop\BgCode\Block\GCodeBlock;
use NPShop\BgCode\Block\MetadataBlock;
use NPShop\BgCode\Block\PrintMetadataBlock;
use NPShop\BgCode\Block\PrinterMetadataBlock;
use NPShop\BgCode\Block\SlicerMetadataBlock;
use NPShop\BgCode\Block\ThumbnailBlock;
use NPShop\BgCode\Compression\DecompressorRegistry;
use NPShop\BgCode\Encoding\DefaultGCodeDecoder;
use NPShop\BgCode\Encoding\MeatPackDecoder;
use NPShop\BgCode\Exception\InvalidFormatException;
use NPShop\BgCode\Header\FileHeader;
use NPShop\BgCode\Reader\BinaryReader;
use NPShop\BgCode\Reader\BlockReader;
use NPShop\BgCode\Thumbnail\ThumbnailFormat;

final class BgCode
{
    private function __construct(private readonly BinaryReader $reader, private readonly FileHeader $header, private readonly DecompressorRegistry $decompressors, private readonly DefaultGCodeDecoder $gcodeDecoder) {}

    public static function open(string $path): self
    {
        $stream = fopen($path, 'rb');
        if ($stream === false) {
            throw new \RuntimeException("Unable to open {$path}");
        }
        return self::fromStream($stream);
    }

    public static function fromStream($stream): self
    {
        $reader = new BinaryReader($stream);
        $magic = $reader->u32();
        $version = $reader->u32();
        $checksumType = $reader->u16();
        if (pack('V', $magic) !== 'GCDE') {
            throw new InvalidFormatException('Invalid GCDE magic.');
        }
        if ($checksumType > 1) {
            throw new InvalidFormatException('Unsupported checksum type.');
        }
        return new self($reader, new FileHeader($magic, $version, $checksumType), new DecompressorRegistry(), new DefaultGCodeDecoder(new MeatPackDecoder()));
    }

    public function header(): FileHeader
    {
        return $this->header;
    }

    /** @return \Generator<int, Block> */
    public function blocks(): \Generator
    {
        $reader = new BlockReader($this->reader, $this->header->checksumType);
        while (($block = $reader->next()) !== null) {
            $decoded = $this
                ->decompressors
                ->get($block->header()->compression)
                ->decompress($block->data(), $block->header()->uncompressedSize);

            yield $this->replaceBlockData($block, $decoded);
        }
    }

    private function replaceBlockData(Block $block, string $decoded): Block
    {
        $h = $block->header();
        $p = $block->parameters();
        $c = $block->checksum();

        return match (true) {
            $block instanceof GCodeBlock => new GCodeBlock($h, $p, $decoded, $c),
            $block instanceof ThumbnailBlock => new ThumbnailBlock($h, $p, $decoded, $c),
            $block instanceof MetadataBlock => match ($block::class) {
                FileMetadataBlock::class => new FileMetadataBlock($h, $p, $decoded, $c),
                PrinterMetadataBlock::class => new PrinterMetadataBlock($h, $p, $decoded, $c),
                PrintMetadataBlock::class => new PrintMetadataBlock($h, $p, $decoded, $c),
                SlicerMetadataBlock::class => new SlicerMetadataBlock($h, $p, $decoded, $c),
                default => $block,
            },
            default => $block,
        };
    }

    public function gcode(): \Generator
    {
        foreach ($this->blocks() as $block) {
            if ($block instanceof GCodeBlock) {
                yield $block->decoded($this->gcodeDecoder);
            }
        }
    }

    public function metadata(): array
    {
        $result = [];
        foreach ($this->blocks() as $block) {
            if ($block instanceof MetadataBlock) {
                $result[$block::class] = $block->values();
            }
        }return $result;
    }

    /** @return ThumbnailBlock[] */
    public function thumbnails(): array
    {
        $out = [];
        foreach ($this->blocks() as $block) {
            if ($block instanceof ThumbnailBlock) {
                $out[] = $block;
            }
        }return $out;
    }
}
