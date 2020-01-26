<?php


namespace Helper;


class TextHelper
{
    /**
     * Search a $needle and replace with $replace in a $text
     *
     * @param  string $needle text to search for
     * @param  string $replace replacing text
     * @param  string $text text to search in
     * @return string|string[]
     */
    public static function searchAndReplace(string $needle, string $replace, string $text)
    {
        return str_replace($needle, $replace, $text);
    }

    /**
     * Search $needle in $text and return true if $needle is found, false otherwise
     * @param string $needle text to search for
     * @param string $text text to search in
     * @return bool true if $needle is present, false otherwise
     */
    public static function doesContain(string $needle, string $text) {
        return strpos($text, $needle) !== false;
    }
}