<?php

declare(strict_types=1);

namespace NPShop\BgCode\Compression;

final class NoneDecompressor implements Decompressor
{
    public function decompress(string $data, int $expectedSize): string
    {
        if (strlen($data) !== $expectedSize) {
            throw new \RuntimeException('Uncompressed size mismatch.');
        }
        return $data;
    }
}
