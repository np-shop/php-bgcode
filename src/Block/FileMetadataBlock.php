<?php

declare(strict_types=1);

namespace NickNickDevelopment\BgCode\Block;

final class FileMetadataBlock extends MetadataBlock
{
    public function type(): BlockType
    {
        return BlockType::FileMetadata;
    }
}
