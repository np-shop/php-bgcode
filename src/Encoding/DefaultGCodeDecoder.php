<?php

declare(strict_types=1);

namespace NickNickDevelopment\BgCode\Encoding;

final class DefaultGCodeDecoder implements GCodeDecoder
{
    public function __construct(private readonly MeatPackDecoder $meatPack) {}

    public function decode(string $data, GCodeEncodingType $encoding): string
    {
        return match ($encoding) {
            GCodeEncodingType::None => $data,
            GCodeEncodingType::MeatPack, GCodeEncodingType::MeatPackComments => $this->meatPack->decode($data),
        };
    }
}
