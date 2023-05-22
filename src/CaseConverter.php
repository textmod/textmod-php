<?php

namespace TextMod;

class CaseConverter
{
    public static function toKebabCase(string $string): string
    {
        return strtolower(preg_replace('/(?<!^)([A-Z])/', '-$1', $string));
    }
}
