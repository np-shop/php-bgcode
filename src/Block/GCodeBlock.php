<?php

declare(strict_types=1);

namespace NickNickDevelopment\BgCode\Block;

use NickNickDevelopment\BgCode\Encoding\GCodeEncodingType;
use NickNickDevelopment\BgCode\Encoding\GCodeDecoder;
use NickNickDevelopment\BgCode\Support\UInt;

final class GCodeBlock extends Block
{
    public function encoding(): GCodeEncodingType
    {
        return GCodeEncodingType::from(UInt::u16($this->parameters));
    }

    public function decoded(GCodeDecoder $decoder): string
    {
        return $decoder->decode($this->data, $this->encoding());
    }

    public function type(): BlockType
    {
        return BlockType::GCode;
    }
}
