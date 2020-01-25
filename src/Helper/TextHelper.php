<?php


namespace Helper;


class TextHelper
{
    /**
     * Search a $needle and replace with $replace in a $text
     *
     * @param  string $needle
     * @param  string $replace
     * @param  string $text
     * @return string|string[]
     */
    public static function searchAndReplace(string $needle, string $replace, string $text)
    {
        return str_replace($needle, $replace, $text);
    }
}