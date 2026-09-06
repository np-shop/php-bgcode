<?php

declare(strict_types=1);

namespace NPShop\BgCode\Handler;

use NPShop\BgCode\Block\Block;
use NPShop\BgCode\Header\BlockHeader;

interface BlockHandler
{
    public function supports(BlockHeader $header): bool;
    public function handle(Block $block): Block;
}
