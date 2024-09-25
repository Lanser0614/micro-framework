<?php

namespace Lanser\MyFreamwork\Core;

use ReflectionClass;

class RequestObject
{
    public function __construct(
        public ReflectionClass $reflection,
        public string          $method,
    )
    {
    }
}