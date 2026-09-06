<?php

declare(strict_types=1);

namespace NPShop\BgCode\Compression;

interface Decompressor
{
    public function decompress(string $data, int $expectedSize): string;
}
