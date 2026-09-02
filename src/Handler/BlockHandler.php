<?php

declare(strict_types=1);

namespace NickNickDevelopment\BgCode\Handler;

use NickNickDevelopment\BgCode\Block\Block;
use NickNickDevelopment\BgCode\Header\BlockHeader;

interface BlockHandler
{
    public function supports(BlockHeader $header): bool;
    public function handle(Block $block): Block;
}
