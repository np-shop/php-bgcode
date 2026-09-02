<?php

declare(strict_types=1);

namespace NickNickDevelopment\BgCode\Handler;

use NickNickDevelopment\BgCode\Block\Block;
use NickNickDevelopment\BgCode\Block\BlockType;
use NickNickDevelopment\BgCode\Block\FileMetadataBlock;
use NickNickDevelopment\BgCode\Block\GCodeBlock;
use NickNickDevelopment\BgCode\Block\PrinterMetadataBlock;
use NickNickDevelopment\BgCode\Block\PrintMetadataBlock;
use NickNickDevelopment\BgCode\Block\SlicerMetadataBlock;
use NickNickDevelopment\BgCode\Block\ThumbnailBlock;
use NickNickDevelopment\BgCode\Header\BlockHeader;

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
