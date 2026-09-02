# php-bgcode

Pure PHP reader for Prusa BGCode files.

This project is intentionally structured around the BGCode concepts used by Prusa's `libbgcode`: a file header followed by typed blocks, per-block compression, block parameters, and block payloads.

## Status

Early architecture / development stage.

Implemented foundation:

- File header
- Typed block model
- Block reader / iterator
- None, Deflate, Heatshrink 11/4 and Heatshrink 12/4 decompression
- Metadata block models
- G-code block model
- MeatPack decoder integration point
- Thumbnail block model
- Streaming `gcode()` API

## Example

```php
use NickNickDevelopment\BgCode\BgCode;

$bgcode = BgCode::open('print.bgcode');

foreach ($bgcode->gcode() as $chunk) {
    file_put_contents('output.gcode', $chunk, FILE_APPEND);
}
```

## Format reference

The upstream reference implementation is [Prusa3D/libbgcode](https://github.com/prusa3d/libbgcode).

The PHP implementation is independent and should remain tested against real BGCode fixtures.
