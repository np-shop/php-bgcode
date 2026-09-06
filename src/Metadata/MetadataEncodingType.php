<?php

declare(strict_types=1);

namespace NPShop\BgCode\Metadata;

enum MetadataEncodingType: int
{
    case INI = 0;
    case JSON = 1;
}
