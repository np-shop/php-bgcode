<?php

declare(strict_types=1);

namespace NPShop\BgCode\Thumbnail;

enum ThumbnailFormat: int
{
    case PNG = 0;
    case JPG = 1;
    case QOI = 2;
}
