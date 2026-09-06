# php-bgcode

PHP reader for Prusa Binary Gcode files.

This library reads Binary Gcode streams into typed blocks and exposes the main file surfaces through `BgCode`.

## Installation

```bash
composer require np-shop/php-bgcode
```

## Usage

```php
use NPShop\BgCode\BgCode;

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

## Block types

`BgCode::blocks()` returns typed block objects.

### Shared methods

| Method | Description | Returns |
| --- | --- | --- |
| `header()` | Returns the parsed block header. | `BlockHeader` |
| `parameters()` | Returns the raw block parameters. | `string` |
| `data()` | Returns the raw block payload. | `string` |
| `checksum()` | Returns the block checksum when present. | `?int` |
| `type()` | Returns the block type enum. | `BlockType` |

### `BlockType::FileMetadata`

| Method | Description | Returns |
| --- | --- | --- |
| `encoding()` | Returns the metadata encoding. | `MetadataEncodingType` |
| `text()` | Returns the raw metadata text. | `string` |
| `values()` | Returns decoded metadata key/value pairs. | `array` |

### `BlockType::GCode`

| Method | Description | Returns |
| --- | --- | --- |
| `encoding()` | Returns the G-code encoding. | `GCodeEncodingType` |
| `decoded(GCodeDecoder $decoder)` | Returns decoded G-code text using the provided decoder. | `string` |

### `BlockType::SlicerMetadata`

| Method | Description | Returns |
| --- | --- | --- |
| `encoding()` | Returns the metadata encoding. | `MetadataEncodingType` |
| `text()` | Returns the raw metadata text. | `string` |
| `values()` | Returns decoded metadata key/value pairs. | `array` |

### `BlockType::PrinterMetadata`

| Method | Description | Returns |
| --- | --- | --- |
| `encoding()` | Returns the metadata encoding. | `MetadataEncodingType` |
| `text()` | Returns the raw metadata text. | `string` |
| `values()` | Returns decoded metadata key/value pairs. | `array` |

### `BlockType::PrintMetadata`

| Method | Description | Returns |
| --- | --- | --- |
| `encoding()` | Returns the metadata encoding. | `MetadataEncodingType` |
| `text()` | Returns the raw metadata text. | `string` |
| `values()` | Returns decoded metadata key/value pairs. | `array` |

### `BlockType::Thumbnail`

| Method | Description | Returns |
| --- | --- | --- |
| `format()` | Returns the thumbnail image format. | `ThumbnailFormat` |
| `width()` | Returns the thumbnail width in pixels. | `int` |
| `height()` | Returns the thumbnail height in pixels. | `int` |

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
