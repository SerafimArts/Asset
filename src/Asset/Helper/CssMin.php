<?php
/**
 * @author Serafim <serafim@sources.ru>
 * @link http://rudev.org/
 * @date 10.08.13 1:56
 * @copyright 2008-2013 RuDev
 * @since 1.1.1
 */
namespace Asset\Helper;

class CssMin
{
    public static function minify($source, $level = 3)
    {
        if ($level === 0)  { return $source; }
        if ($level !== 1)  { $source = str_replace(["\n\r", "\r\n", "\n", "\r", "\t"], '', $source); }
        if ($level === 2)  { $source = str_replace('}', '}' . "\n", $source); }

        while (strstr($source, '  ')) {
            $source = str_replace('  ', ' ', $source);
        }
        $source = str_replace(
            [
                '; ', ' {', '{ ', ' }',
                '} ', ': ', ' :', ', ',
                ' ,', ' >', '> ', '* ',
                ' *',' ;', ' \\', '\\ ',
                ' /', '/ ', '+ ', ' +',
                '[ ',' [', '] ', ' ]',
                ' !', '0.'
            ],
            [
                ';', '{', '{', '}',
                '}', ':', ':', ',',
                ',', '>', '>', '*',
                '*', ';', '\\', '\\',
                '/', '/', '+', '+',
                '[', '[', ']', ']',
                '!', '.'
            ],
            $source
        );
        $source = preg_replace('#/\*(.*?)\*/#isu', '', $source);

        return $source;
    }
}