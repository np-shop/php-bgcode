<?php

declare(strict_types=1);

namespace NickNickDevelopment\BgCode\Block;

use NickNickDevelopment\BgCode\Header\BlockHeader;

abstract class Block
{
    public function __construct(
        protected readonly BlockHeader $header,
        protected readonly string $parameters,
        protected readonly string $data,
        protected readonly ?int $checksum,
    ) {}

    public function header(): BlockHeader
    {
        return $this->header;
    }
    public function parameters(): string
    {
        return $this->parameters;
    }
    public function data(): string
    {
        return $this->data;
    }
    public function checksum(): ?int
    {
        return $this->checksum;
    }
    abstract public function type(): BlockType;
}
