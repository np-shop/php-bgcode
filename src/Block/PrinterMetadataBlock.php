<?php

declare(strict_types=1);

namespace NPShop\BgCode\Block;

final class PrinterMetadataBlock extends MetadataBlock
{
    public function type(): BlockType
    {
        return BlockType::PrinterMetadata;
    }
}
