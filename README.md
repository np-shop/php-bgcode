# php-bgcode

Pure PHP reader for Prusa BGCode files.

This library reads BGCode streams into typed blocks and exposes the main file surfaces through `BgCode`.

## Installation

```bash
composer require nicknick-development/php-bgcode
```

## Usage

```php
use NickNickDevelopment\BgCode\BgCode;

$bgcode = BgCode::open('print.bgcode');
```

## `BgCode::open(string $path): self`

Opens a BGCode file from disk and returns a `BgCode` instance.

```php
$bgcode = BgCode::open('print.bgcode');
```

## `BgCode::fromStream($stream): self`

Builds a `BgCode` instance from an open binary stream resource.

```php
$handle = fopen('print.bgcode', 'rb');
$bgcode = BgCode::fromStream($handle);
```

## `BgCode::header(): FileHeader`

Returns the parsed file header.

```php
$header = $bgcode->header();

var_dump($header->magicString());
var_dump($header->version);
var_dump($header->checksumType);
```

## `BgCode::blocks(): \Generator`

Returns every parsed block in order as a generator.

```php
foreach ($bgcode->blocks() as $block) {
    var_dump($block::class);
}
```

## `BgCode::gcode(): \Generator`

Returns decoded G-code chunks from all G-code blocks.

```php
foreach ($bgcode->gcode() as $chunk) {
    file_put_contents('output.gcode', $chunk, FILE_APPEND);
}
```

## `BgCode::metadata(): array`

Returns metadata blocks keyed by block class name.

```php
$metadata = $bgcode->metadata();

foreach ($metadata as $class => $values) {
    var_dump($class, $values);
}
```

## `BgCode::thumbnails(): array`

Returns all thumbnail blocks.

```php
foreach ($bgcode->thumbnails() as $thumbnail) {
    var_dump($thumbnail->format(), $thumbnail->width(), $thumbnail->height());
}
```

## Tests

The repository includes a fixture at `tests/Fixtures/test.bgcode` used by the unit tests for metadata, G-code, header, and thumbnail reading.

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

## Format reference

The upstream reference implementation is [Prusa3D/libbgcode](https://github.com/prusa3d/libbgcode).

The PHP implementation is independent and should remain tested against real BGCode fixtures.
