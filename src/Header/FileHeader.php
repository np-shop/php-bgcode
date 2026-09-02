<?php

declare(strict_types=1);

namespace NickNickDevelopment\BgCode\Header;

final readonly class FileHeader
{
    public function __construct(
        public int $magic,
        public int $version,
        public int $checksumType,
    ) {}

    public function magicString(): string
    {
        return pack('V', $this->magic);
    }
}
