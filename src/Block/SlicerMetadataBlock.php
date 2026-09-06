<?php

declare(strict_types=1);

namespace NPShop\BgCode\Block;

final class SlicerMetadataBlock extends MetadataBlock
{
    public function type(): BlockType
    {
        return BlockType::SlicerMetadata;
    }
}
