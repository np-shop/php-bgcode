<?php

declare(strict_types=1);

namespace NPShop\BgCode\Block;

enum BlockType: int
{
    case FileMetadata = 0;
    case GCode = 1;
    case SlicerMetadata = 2;
    case PrinterMetadata = 3;
    case PrintMetadata = 4;
    case Thumbnail = 5;
}
