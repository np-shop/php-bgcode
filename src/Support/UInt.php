<?php

declare(strict_types=1);

namespace NickNickDevelopment\BgCode\Support;

final class UInt
{
    public static function u16(string $bytes, int $offset = 0): int
    {
        $value = unpack('v', substr($bytes, $offset, 2));
        if ($value === false) {
            throw new \RuntimeException('Unable to read uint16.');
        }
        return $value[1];
    }

    public static function u32(string $bytes, int $offset = 0): int
    {
        $value = unpack('V', substr($bytes, $offset, 4));
        if ($value === false) {
            throw new \RuntimeException('Unable to read uint32.');
        }
        return $value[1];
    }
}
