<?php

declare(strict_types=1);

namespace NPShop\BgCode\Handler;

use NPShop\BgCode\Block\Block;
use NPShop\BgCode\Block\BlockType;
use NPShop\BgCode\Block\FileMetadataBlock;
use NPShop\BgCode\Block\GCodeBlock;
use NPShop\BgCode\Block\PrinterMetadataBlock;
use NPShop\BgCode\Block\PrintMetadataBlock;
use NPShop\BgCode\Block\SlicerMetadataBlock;
use NPShop\BgCode\Block\ThumbnailBlock;
use NPShop\BgCode\Header\BlockHeader;

final class DefaultBlockHandler implements BlockHandler
{
    public function supports(BlockHeader $header): bool
    {
        return true;
    }
    public function handle(Block $block): Block
    {
        return $block;
    }
}
