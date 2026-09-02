<?php

declare(strict_types=1);

namespace NickNickDevelopment\BgCode\Block;

use NickNickDevelopment\BgCode\Support\UInt;
use NickNickDevelopment\BgCode\Thumbnail\ThumbnailFormat;

final class ThumbnailBlock extends Block
{
    public function format(): ThumbnailFormat
    {
        return ThumbnailFormat::from(UInt::u16($this->parameters, 0));
    }
    public function width(): int
    {
        return UInt::u16($this->parameters, 2);
    }
    public function height(): int
    {
        return UInt::u16($this->parameters, 4);
    }
    public function type(): BlockType
    {
        return BlockType::Thumbnail;
    }
}
