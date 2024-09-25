<?php
declare(strict_types=1);

namespace Lanser\MyFreamwork\Core\Attributes;

use Attribute;

#[Attribute]
class Entity
{
    public function __construct(
        public readonly string $table,
    )
    {}
}