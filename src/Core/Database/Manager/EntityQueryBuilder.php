<?php
declare(strict_types=1);

namespace Lanser\MyFreamwork\Core\Database\Manager;

use Illuminate\Support\Collection;
use Lanser\MyFreamwork\Core\Database\Mapper\EntityMapperObject;
use Lanser\MyFreamwork\Core\Database\Trait\HasQueryBuilderTrait;

class EntityQueryBuilder
{
    use HasQueryBuilderTrait;

    public function query(string $table): static
    {
        $this->setTable($table);
        return $this;
    }


    public function where(string $column, string $operator, mixed $value): static
    {
        if (is_string($value)) {
            $value = "'" . $value . "'";
        }

        $this->setWhere('where', " " . $column . " " . $operator . " " . $value);
        return $this;
    }

    public function andWhere(string $column, string $operator, mixed $value): static
    {
        if (is_string($value)) {
            $value = "'" . $value . "'";
        }

        $this->setWhere('AND', " " . $column . " " . $operator . " " . $value);
        return $this;
    }

    public function orWhere(string $column, string $operator, mixed $value): static
    {
        $this->setWhere('OR', " " . $column . " " . $operator . " " . $value);
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): static
    {
        $this->setOrderBy($column, $direction);
        return $this;
    }

    public function oneItem(): static
    {
        $this->setLimit(0, 1);
        return $this;
    }

    public function save(EntityMapperObject $entityMapperObject, $updateWithOutWhere = false): static
    {
        $this->saveOrUpdateRecord($entityMapperObject, $updateWithOutWhere);
        return $this;
    }

    public function persist(): Collection
    {
        return collect($this->executeQuery());
    }

    public function persistSave(): bool|array
    {
        return $this->executeQueryForWrite();
    }
}