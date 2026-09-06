<?php

declare(strict_types=1);

namespace NPShop\BgCode\Compression;

final class DecompressorRegistry
{
    /** @var array<int, Decompressor> */
    private array $decompressors;

    public function __construct()
    {
        $this->decompressors = [
            CompressionType::None->value => new NoneDecompressor(),
            CompressionType::Deflate->value => new DeflateDecompressor(),
            CompressionType::Heatshrink11_4->value => new HeatshrinkDecompressor(11, 4),
            CompressionType::Heatshrink12_4->value => new HeatshrinkDecompressor(12, 4),
        ];
    }

    public function get(CompressionType $type): Decompressor
    {
        return $this->decompressors[$type->value] ?? throw new \RuntimeException('No decompressor registered.');
    }
}
