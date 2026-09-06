<?php

declare(strict_types=1);

namespace NPShop\BgCode\Block;

use NPShop\BgCode\Encoding\GCodeEncodingType;
use NPShop\BgCode\Encoding\GCodeDecoder;
use NPShop\BgCode\Support\UInt;

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
