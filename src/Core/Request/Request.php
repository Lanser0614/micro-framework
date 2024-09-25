<?php
declare(strict_types=1);

namespace Lanser\MyFreamwork\Core\Request;

class Request
{
    public static function uri(): string
    {
        return trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    }

    public static function method(): mixed
    {
        return $_SERVER['REQUEST_METHOD'];
    }


    public static function getHashRequest()
    {
        return self::uri() . '|' . self::method();
    }
}