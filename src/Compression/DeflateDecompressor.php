<?php

declare(strict_types=1);

namespace NickNickDevelopment\BgCode\Compression;

final class DeflateDecompressor implements Decompressor
{
    public function decompress(string $data, int $expectedSize): string
    {
        $out = @zlib_decode($data);
        if ($out === false) {
            $out = @gzuncompress($data);
        }
        if ($out === false || strlen($out) !== $expectedSize) {
            throw new \RuntimeException('Deflate decode failed or size mismatch.');
        }
        return $out;
    }
}
