<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use NickNickDevelopment\BgCode\BgCode;

$bgcode = BgCode::open($argv[1] ?? 'test.bgcode');

print_r($bgcode->thumbnails());

foreach ($bgcode->blocks() as $i => $block) {
    printf("%03d type=%s compression=%s size=%d\n", $i, $block->type()->name, $block->header()->compression->name, $block->header()->uncompressedSize);
}
