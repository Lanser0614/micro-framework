<?php

namespace Lanser\MyFreamwork\Core\Database\Mapper;

class EntityMapperObject
{
    public function __construct(
        public readonly ?string $primaryKey = null,
        public readonly ?array  $data = null,
    )
    {
    }
}