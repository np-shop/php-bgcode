<?php

declare(strict_types=1);

namespace NickNickDevelopment\BgCode\Block;

final class PrinterMetadataBlock extends MetadataBlock
{
    public function type(): BlockType
    {
        return BlockType::PrinterMetadata;
    }
}
