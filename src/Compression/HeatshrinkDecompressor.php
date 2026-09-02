<?php

declare(strict_types=1);

namespace NickNickDevelopment\BgCode\Compression;

final class HeatshrinkDecompressor implements Decompressor
{
    public function __construct(private readonly int $windowBits, private readonly int $lookaheadBits) {}

    public function decompress(string $input, int $expectedSize): string
    {
        $windowSize = 1 << $this->windowBits;
        $mask = $windowSize - 1;
        $window = array_fill(0, $windowSize, 0);
        $inputLength = strlen($input);
        $inputPos = 0;
        $bitIndex = 0;
        $currentByte = 0;
        $headIndex = 0;
        $output = '';
        $getBits = function (int $count) use (&$input, &$inputLength, &$inputPos, &$bitIndex, &$currentByte): ?int {
            $value = 0;
            for ($i = 0; $i < $count; ++$i) {
                if ($bitIndex === 0) {
                    if ($inputPos >= $inputLength) {
                        return null;
                    }
                    $currentByte = ord($input[$inputPos++]);
                    $bitIndex = 0x80;
                }
                $value = ($value << 1) | (($currentByte & $bitIndex) !== 0 ? 1 : 0);
                $bitIndex >>= 1;
            }
            return $value;
        };
        while (strlen($output) < $expectedSize) {
            $tag = $getBits(1);
            if ($tag === null) {
                throw new \RuntimeException('Unexpected end of Heatshrink stream.');
            }
            if ($tag === 1) {
                $byte = $getBits(8);
                if ($byte === null) {
                    throw new \RuntimeException('Unexpected end of Heatshrink literal.');
                }
                $window[$headIndex & $mask] = $byte;
                ++$headIndex;
                $output .= chr($byte);
                continue;
            }
            $index = $getBits($this->windowBits);
            $count = $getBits($this->lookaheadBits);
            if ($index === null || $count === null) {
                throw new \RuntimeException('Unexpected end of Heatshrink back-reference.');
            }
            ++$index;
            ++$count;
            if ($index > $windowSize) {
                throw new \RuntimeException('Invalid Heatshrink back-reference.');
            }
            for ($i = 0; $i < $count && strlen($output) < $expectedSize; ++$i) {
                $byte = $window[($headIndex - $index) & $mask];
                $output .= chr($byte);
                $window[$headIndex & $mask] = $byte;
                ++$headIndex;
            }
        }
        return $output;
    }
}
