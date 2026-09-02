<?php

declare(strict_types=1);

namespace NickNickDevelopment\BgCode\Block;

final class SlicerMetadataBlock extends MetadataBlock
{
    public function type(): BlockType
    {
        return BlockType::SlicerMetadata;
    }
}
