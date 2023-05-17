<?php

namespace TextMod;

class CaseConverter
{
    /**
     * Converts a string to kebab-case.
     *
     * @param string $string
     * @return string
     */
    public static function toKebabCase(string $string): string
    {
        return strtolower(preg_replace('/(?<!^)([A-Z])/', '-$1', $string));
    }
}
