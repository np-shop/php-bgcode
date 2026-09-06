<?php

declare(strict_types=1);

namespace NPShop\BgCode\Reader;

final class BinaryReader
{
    public function __construct(private $stream) {}

    public function position(): int
    {
        return ftell($this->stream);
    }

    public function eof(): bool
    {
        return feof($this->stream);
    }

    public function seek(int $offset): void
    {
        if (fseek($this->stream, $offset, SEEK_SET) !== 0) {
            throw new \RuntimeException("Unable to seek to {$offset}.");
        }
    }

    public function read(int $length): string
    {
        if ($length < 0) {
            throw new \InvalidArgumentException('Length cannot be negative.');
        }
        $data = '';
        while (strlen($data) < $length) {
            $chunk = fread($this->stream, $length - strlen($data));
            if ($chunk === false || $chunk === '') {
                throw new \RuntimeException(sprintf('Unexpected EOF at 0x%X.', $this->position()));
            }
            $data .= $chunk;
        }
        return $data;
    }

    public function u16(): int
    {
        $v = unpack('v', $this->read(2));
        return $v[1];
    }

    public function u32(): int
    {
        $v = unpack('V', $this->read(4));
        return $v[1];
    }

    public function size(): int
    {
        $stats = fstat($this->stream);
        if ($stats === false) {
            throw new \RuntimeException('Unable to stat stream.');
        } return $stats['size'];
    }
}
