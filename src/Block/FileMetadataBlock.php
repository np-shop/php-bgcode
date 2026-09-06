<?php

declare(strict_types=1);

namespace NPShop\BgCode\Block;

final class FileMetadataBlock extends MetadataBlock
{
    public function type(): BlockType
    {
        return BlockType::FileMetadata;
    }
}
