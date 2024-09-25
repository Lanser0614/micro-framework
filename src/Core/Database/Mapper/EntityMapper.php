<?php
declare(strict_types=1);

namespace Lanser\MyFreamwork\Core\Database\Mapper;

use Exception;
use Lanser\MyFreamwork\Core\Attributes\Column;
use Lanser\MyFreamwork\Core\Attributes\Entity;
use Lanser\MyFreamwork\Core\Attributes\PrimaryKey;
use ReflectionClass;
use ReflectionException;

class EntityMapper
{
    public ?string $primaryKey = null;
    public array $data = [];

    /**
     * @param object $class
     * @return array|EntityMapperObject
     * @throws Exception
     */
    public function mapToDatabaseFill(object $class): array|EntityMapperObject
    {
        $ref = new ReflectionClass($class);
        if (empty($ref->getAttributes(Entity::class))) {
            throw new Exception("Entity mapper does not have attributes defined");
        }

        $entityData = [];

        foreach ($ref->getProperties() as $property) {
            if (!empty($property->getAttributes(PrimaryKey::class))) {
                $this->primaryKey = $property->getName();
            }
            $entityData[$property->getAttributes(Column::class)[0]->getArguments()['columnName']] = $property->getValue($class);
        }

        return new EntityMapperObject($this->primaryKey, $entityData);
    }


    /**
     * @param string $class
     * @param array $data
     * @return mixed
     * @throws ReflectionException
     * @throws Exception
     */
    public function mapToEntityFill(string $class, array $data): mixed
    {
        $ref = new ReflectionClass($class);
        if (empty($ref->getAttributes(Entity::class))) {
            throw new Exception("Entity mapper does not have attributes defined");
        }

        $entity = new $class();
        foreach ($ref->getProperties() as $property) {
            $key = $property->getAttributes(Column::class)[0]->getArguments()['columnName'];
            $property->setValue($entity, $data[$key]);
        }

        return $entity;
    }
}


