<?php

declare(strict_types=1);

namespace NPShop\BgCode\Encoding;

final class MeatPackDecoder
{
    public function decode(string $src): string
    {
        $unbinarizing = false;
        $nospaceEnabled = false;
        $cmdActive = false;
        $charBuf = 0;
        $cmdCount = 0;
        $fullCharQueue = 0;
        $charOutBuf = [];
        $out = '';
        $addSpace = false;
        $getChar = static function (int $c) use (&$nospaceEnabled): int {
            return match ($c) {
                0 => ord('0'),1 => ord('1'),2 => ord('2'),3 => ord('3'),4 => ord('4'),5 => ord('5'),6 => ord('6'),7 => ord('7'),8 => ord('8'),9 => ord('9'),10 => ord('.'),11 => ord($nospaceEnabled ? 'E' : ' '),12 => ord("\n"),13 => ord('G'),14 => ord('X'),default => 0
            };
        };
        $unpackChars = static function (int $pk) use ($getChar): array {
            $flags = 0;
            $c0 = 0;
            $c1 = 0;
            if (($pk & 0x0F) === 0x0F) {
                $flags |= 1;
            } else {
                $c0 = $getChar($pk & 0x0F);
            } if (($pk & 0xF0) === 0xF0) {
                $flags |= 2;
            } else {
                $c1 = $getChar(($pk >> 4) & 0x0F);
            } return [$flags,$c0,$c1];
        };
        $handleCommand = static function (int $c) use (&$unbinarizing, &$nospaceEnabled): void {
            switch ($c) {
                case 251:$unbinarizing = true;
                    break;
                case 250:$unbinarizing = false;
                    break;
                case 247:$nospaceEnabled = true;
                    break;
                case 246:$nospaceEnabled = false;
                    break;
                case 249:$unbinarizing = false;
                    break;
                case 248:break;
            }
        };
        $emit = static function (int $c) use (&$charOutBuf): void {
            $charOutBuf[] = $c;
        };
        $handleRx = static function (int $c) use (&$unbinarizing, &$fullCharQueue, &$charBuf, &$emit, $unpackChars): void {
            if ($unbinarizing) {
                if ($fullCharQueue > 0) {
                    $emit($c);
                    if ($charBuf > 0) {
                        $emit($charBuf);
                        $charBuf = 0;
                    }--$fullCharQueue;
                    return;
                }[$res,$c0,$c1] = $unpackChars($c);
                if (($res & 1) !== 0) {
                    ++$fullCharQueue;
                    if (($res & 2) !== 0) {
                        ++$fullCharQueue;
                    } else {
                        $charBuf = $c1;
                    }
                } else {
                    $emit($c0);
                    if ($c0 !== ord("\n")) {
                        if (($res & 2) !== 0) {
                            ++$fullCharQueue;
                        } else {
                            $emit($c1);
                        }
                    }
                }
            } else {
                $emit($c);
            }
        };
        $isParam = static fn(int $c): bool => in_array(chr($c), ['X','Y','Z','E','F','I','J','R','S','G','P','W','H','C','A'], true);
        for ($i = 0,$n = strlen($src);$i < $n;++$i) {
            $c = ord($src[$i]);
            if ($c === 0xFF) {
                if ($cmdCount > 0) {
                    $cmdActive = true;
                    $cmdCount = 0;
                } else {
                    ++$cmdCount;
                }
            } else {
                if ($cmdActive) {
                    $handleCommand($c);
                    $cmdActive = false;
                } else {
                    if ($cmdCount > 0) {
                        $handleRx(0xFF);
                        $cmdCount = 0;
                    }$handleRx($c);
                }
            }if ($charOutBuf) {
                foreach ($charOutBuf as $cOut) {
                    $currLen = strlen($out);
                    $newLine = false;
                    if ($cOut === ord('G') && ($currLen === 0 || $out[$currLen - 1] === "\n")) {
                        $addSpace = true;
                        $newLine = true;
                    } elseif ($cOut === ord("\n")) {
                        $addSpace = false;
                    }if (!$newLine && $addSpace && ($currLen === 0 || $out[$currLen - 1] !== ' ') && $isParam($cOut)) {
                        $out .= ' ';
                    }$out .= chr($cOut);
                } $charOutBuf = [];
            }
        }
        return $out;
    }
}
