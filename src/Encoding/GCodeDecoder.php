<?php

declare(strict_types=1);

namespace NickNickDevelopment\BgCode\Encoding;

interface GCodeDecoder
{
    public function decode(string $data, GCodeEncodingType $encoding): string;
}
