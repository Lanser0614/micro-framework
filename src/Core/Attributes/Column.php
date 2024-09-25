<?php
declare(strict_types=1);

namespace Lanser\MyFreamwork\Core\Attributes;

use Attribute;

#[Attribute]
class Column
{
    public function __construct(
        public string $columnName,
    )
    {
    }
}