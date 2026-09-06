<?php

declare(strict_types=1);

namespace NPShop\BgCode\Compression;

enum CompressionType: int
{
    case None = 0;
    case Deflate = 1;
    case Heatshrink11_4 = 2;
    case Heatshrink12_4 = 3;
}
