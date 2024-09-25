<?php

namespace Lanser\MyFreamwork\Core\Database\Trait;

use Lanser\MyFreamwork\Core\Database\Connection\DBConnection;
use Lanser\MyFreamwork\Core\Database\Mapper\EntityMapperObject;

trait HasQueryBuilderTrait
{

    private string $table = '';
    private string $sql = '';
    protected array $where = [];
    private array $orderBy = [];
    private array $limit = [];
    private array $values = [];
    private array $bindValues = [];

    protected function setTable(string $table): void
    {
        $this->table = $table;
    }

    protected function setSql($query): void
    {
        $this->sql = $query;
    }

    protected function getSql()
    {
        return $this->sql;
    }

    protected function resetSql()
    {
        $this->sql = '';
    }

    protected function setWhere($operator, $condition)
    {

        $array = ['operator' => $operator, 'condition' => $condition];
        array_push($this->where, $array);

    }

    protected function resetWhere()
    {
        $this->where = [];
    }

    protected function setOrderBy($name, $expression)
    {

        array_push($this->orderBy, $this->getAttributeName($name) . ' ' . $expression);

    }

    protected function resetOrderBy()
    {
        $this->orderBy = [];
    }

    protected function setLimit($from, $number)
    {

        $this->limit['from'] = (int)$from;
        $this->limit['number'] = (int)$number;

    }

    protected function resetLimit()
    {
        unset($this->limit['from']);
        unset($this->limit['number']);
    }


    protected function addValue($attribute, $value)
    {

        $this->values[$attribute] = $value;
        array_push($this->bindValues, $value);

    }

    protected function removeValues()
    {
        $this->values = [];
        $this->bindValues = [];
    }


    protected function resetQuery()
    {

        $this->resetSql();
        $this->resetWhere();
        $this->resetOrderBy();
        $this->resetLimit();
        $this->removeValues();

    }

    protected function executeQuery(): bool|array
    {

        $query = 'select * from ' . $this->table;
        $query .= $this->sql;

        $query = $this->getWhereQuery($query);

        if (!empty($this->orderBy)) {
            $query .= ' ORDER BY ' . implode(', ', $this->orderBy);
        }
        if (!empty($this->limit)) {
            $query .= ' limit ' . $this->limit['from'] . ' , ' . $this->limit['number'] . ' ';
        }
        $query .= ' ;';
        $pdoInstance = DBConnection::getDBConnectionInstance();
        $statement = $pdoInstance->prepare($query);
        sizeof($this->bindValues) > 0 ? $statement->execute($this->bindValues) : $statement->execute();
        return $statement->fetchAll();
    }

    protected function executeQueryForWrite(): bool|array
    {

        $query = $this->sql;
        $query = $this->getWhereQuery($query);
        $query .= ' ;';

        $pdoInstance = DBConnection::getDBConnectionInstance();
        $statement = $pdoInstance->prepare($query);
        sizeof($this->bindValues) > 0 ? $statement->execute($this->bindValues) : $statement->execute();
        return $statement->fetchAll();
    }

    protected function getCount()
    {

        $query = '';
        $query .= "SELECT COUNT(*) FROM " . $this->getTableName();

        $query = $this->getWhereQuery($query);
        $query .= ' ;';

        $pdoInstance = DBConnection::getDBConnectionInstance();
        $statement = $pdoInstance->prepare($query);
        if (sizeof($this->bindValues) > sizeof($this->values)) {
            sizeof($this->bindValues) > 0 ? $statement->execute($this->bindValues) : $statement->execute();
        } else {
            sizeof($this->values) > 0 ? $statement->execute(array_values($this->values)) : $statement->execute();
        }
        return $statement->fetchColumn();
    }

    protected function getTableName(): string
    {

        return ' `' . $this->table . '`';
    }

    protected function getAttributeName($attribute)
    {

        return ' `' . $this->table . '`.`' . $attribute . '` ';
    }

    /**
     * @param string $query
     * @return string
     */
    private function getWhereQuery(string $query): string
    {
        if (!empty($this->where)) {

            $whereString = '';
            foreach ($this->where as $where) {
                $whereString == '' ? $whereString .= $where['condition'] : $whereString .= ' ' . $where['operator'] . ' ' . $where['condition'];
            }
            $query .= ' WHERE ' . $whereString;
        }
        return $query;
    }


    protected function saveOrUpdateRecord(EntityMapperObject $entityMapperObject, $updateWithOutWhere = false): static
    {

        if (isset($entityMapperObject->data[$entityMapperObject->primaryKey]) && $entityMapperObject->data[$entityMapperObject->primaryKey]) {
//            if ($updateWithOutWhere === false || empty($this->where)) {
//                throw new \RuntimeException('Cannot update update without where.');
//            }
            // Update existing record
            $fields = collect($entityMapperObject->data)->map(function ($item, $key) {
                if (is_string($item)) {
                    $item = "'" . $item . "'";
                }

                return $key . '=' . $item;
            })->implode(', ');
            $sql = "UPDATE {$this->table} SET " .  $fields;
        } else {

            $columns = implode(', ', collect($entityMapperObject->data)->whereNotNull()->keys()->toArray());
            $placeholders = implode(', ', collect($entityMapperObject->data)->whereNotNull()->map(function ($item) {
                if (is_string($item)) {
                    $item = "'" . $item . "'";
                }

                return $item;
            })->values()->toArray());
            $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        }

        $this->sql = $sql;
        return $this;
    }
}