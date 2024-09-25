<?php
declare(strict_types=1);

namespace Lanser\MyFreamwork\Core\Attributes;

use Attribute;

#[Attribute]
class Route
{

    public function __construct(
        public readonly string $route,
        public readonly string $method,
        public readonly string $prefix = '',
    )
    {
    }

}