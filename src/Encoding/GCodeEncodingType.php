<?php

declare(strict_types=1);

namespace NPShop\BgCode\Encoding;

enum GCodeEncodingType: int
{
    case None = 0;
    case MeatPack = 1;
    case MeatPackComments = 2;
}
