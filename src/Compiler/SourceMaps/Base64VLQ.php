<?php
namespace Serafim\Asset\Compiler\SourceMaps;

use Exception;

/**
 * Class Base64VLQ
 * based on bspot's encode/decode Base64VLQ class
 *
 * @package Serafim\Asset\Compiler\SourceMaps
 */
class Base64VLQ
{
    /**
     * Char map
     *
     * foreach (str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/') as $i => $char) {
     *      $this->char2int[$char] = $i;
     *      $this->int2char[$i]    = $char;
     * }
     *
     * @var array
     */
    protected $int2char  = [
        0 => 'A',  1 => 'B',  2 => 'C',  3 => 'D',  4 => 'E',  5 => 'F',
        6 => 'G',  7 => 'H',  8 => 'I',  9 => 'J',  10 => 'K', 11 => 'L',
        12 => 'M', 13 => 'N', 14 => 'O', 15 => 'P', 16 => 'Q', 17 => 'R',
        18 => 'S', 19 => 'T', 20 => 'U', 21 => 'V', 22 => 'W', 23 => 'X',
        24 => 'Y', 25 => 'Z', 26 => 'a', 27 => 'b', 28 => 'c', 29 => 'd',
        30 => 'e', 31 => 'f', 32 => 'g', 33 => 'h', 34 => 'i', 35 => 'j',
        36 => 'k', 37 => 'l', 38 => 'm', 39 => 'n', 40 => 'o', 41 => 'p',
        42 => 'q', 43 => 'r', 44 => 's', 45 => 't', 46 => 'u', 47 => 'v',
        48 => 'w', 49 => 'x', 50 => 'y', 51 => 'z', 52 => '0', 53 => '1',
        54 => '2', 55 => '3', 56 => '4', 57 => '5', 58 => '6', 59 => '7',
        60 => '8', 61 => '9', 62 => '+', 63 => '/'
    ];
    protected $char2int  = [];

    /**
     * @var int
     */
    protected $shift = 5;

    /**
     * == (1 << shift) == 0b00011111
     * @var int
     */
    protected $mask = 0x1F;

    /**
     * == (mask - 1)   == 0b00100000
     * @var int
     */
    protected $continuationBit = 0x20;

    /**
     *
     */
    public function __construct()
    {
        $this->char2int = array_flip($this->int2char);
    }


    /**
     * Convert from a two-complement value to a value where the sign bit is
     * is placed in the least significant bit.  For example, as decimals:
     *   1 becomes 2 (10 binary), -1 becomes 3 (11 binary)
     *   2 becomes 4 (100 binary), -2 becomes 5 (101 binary)
     * We generate the value for 32 bit machines, hence
     *   -2147483648 becomes 1, not 4294967297,
     * even on a 64 bit machine.
     *
     * @param $aValue
     * @return int
     */
    public function toVLQSigned($aValue)
    {
        return 0xffffffff &
            ($aValue < 0
                ? ((-$aValue) << 1) + 1
                : ($aValue << 1) + 0
            );
    }

    /**
     * Convert to a two-complement value from a value where the sign bit is
     * is placed in the least significant bit. For example, as decimals:
     *   2 (10 binary) becomes 1, 3 (11 binary) becomes -1
     *   4 (100 binary) becomes 2, 5 (101 binary) becomes -2
     * We assume that the value was generated with a 32 bit machine in mind.
     * Hence
     *   1 becomes -2147483648
     * even on a 64 bit machine.
     *
     * @param $aValue
     * @return int|number
     */
    public function fromVLQSigned($aValue)
    {
        return $aValue & 1
            ? $this->zeroFill(~$aValue + 2, 1) | (-1 - 0x7fffffff)
            : $this->zeroFill($aValue, 1);
    }

    /**
     * Return the base 64 VLQ encoded value.
     *
     * @param $aValue
     * @return string
     * @throws Exception
     */
    public function encode($aValue)
    {
        $encoded = '';
        $vlq = $this->toVLQSigned($aValue);

        do {
            $digit  = $vlq & $this->mask;
            $vlq    = $this->zeroFill($vlq, $this->shift);
            if ($vlq > 0) {
                $digit |= $this->continuationBit;
            }
            $encoded .= $this->base64Encode($digit);
        } while ($vlq > 0);

        return $encoded;
    }

    /**
     * Return the value decoded from base 64 VLQ.
     *
     * @param $encoded
     * @return int|number
     * @throws Exception
     */
    public function decode(&$encoded)
    {
        $vlq = 0;

        $i = 0;
        do {
            $digit  = $this->base64Decode($encoded[$i]);
            $vlq   |= ($digit & $this->mask) << ($i * $this->shift);
            $i++;
        } while ($digit & $this->continuationBit);

        $encoded = substr($encoded, $i);
        return $this->fromVLQSigned($vlq);
    }

    /**
     * Right shift with zero fill.
     *
     * @param number $a number to shift
     * @param nunber $b number of bits to shift
     * @return number
     */
    public function zeroFill($a, $b)
    {
        return ($a >= 0)
            ? ($a >> $b)
            : ($a >> $b) & (PHP_INT_MAX >> ($b - 1));
    }

    /**
     * Encode single 6-bit digit as base64.
     *
     * @param number $number
     * @return string
     */
    public function base64Encode($number)
    {
        if ($number < 0 || $number > 63) {
            throw new Exception('Must be between 0 and 63: ' . $number);
        }
        return $this->int2char[$number];
    }

    /**
     * Decode single 6-bit digit from base64
     *
     * @param string $char
     * @return number
     */
    public function base64Decode($char)
    {
        if (!array_key_exists($char, $this->char2int)) {
            throw new Exception('Not a valid base 64 digit: ' . $char);
        }
        return $this->char2int[$char];
    }
}