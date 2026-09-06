<?php

declare(strict_types=1);

namespace NPShop\BgCode\Block;

use NPShop\BgCode\Header\BlockHeader;
use NPShop\BgCode\Metadata\MetadataEncodingType;
use NPShop\BgCode\Support\UInt;

abstract class MetadataBlock extends Block
{
    public function encoding(): MetadataEncodingType
    {
        return MetadataEncodingType::from(UInt::u16($this->parameters));
    }

    public function text(): string
    {
        return $this->data;
    }

    public function values(): array
    {
        $encoding = $this->encoding();
        if ($encoding === MetadataEncodingType::JSON) {
            $decoded = json_decode($this->data, true);
            if (!is_array($decoded)) {
                throw new \RuntimeException('Invalid JSON metadata.');
            }
            return $decoded;
        }

        $result = [];
        foreach (preg_split('/\r?\n/', trim($this->data)) as $line) {
            if ($line === '' || str_starts_with($line, ';') || !str_contains($line, '=')) {
                continue;
            }
            [$key, $value] = explode('=', $line, 2);
            $result[trim($key)] = trim($value);
        }
        return $result;
    }
}
