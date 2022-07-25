<?php

namespace Battleship\App\Database\Model;

use Battleship\App\Database\QueryBuilder;
use Exception;

abstract class AbstractModel
{
    protected string $tableName;
    protected string $entityClassName;

    public function getQueryBuilder(): QueryBuilder
    {
        return new QueryBuilder($this->entityClassName);
    }

    /**
     * @return QueryBuilder ->from($tableName)
     * @throws Exception
     */
    public function query(): QueryBuilder
    {
        return $this->getQueryBuilder()->from($this->tableName);
    }

    /**
     * @param array $params
     * @return bool успешно или нет
     * @throws Exception
     */
    public function insert(array $params): bool
    {
        return $this->getQueryBuilder()->insert($this->tableName, $params);
    }

    /**
     * @param string $attribute
     * @param array $oldValue
     * @param array $newValue
     * @return bool успешно или нет
     * @throws Exception
     */
    public function update(string $attribute, mixed $oldValue, mixed $newValue): bool
    {
        return $this->getQueryBuilder()->update($this->tableName, $attribute, $oldValue, $newValue);
    }

    public function delete(string $attribute, mixed $value): bool
    {
        return $this->getQueryBuilder()->delete($this->tableName, $attribute, $value);
    }
}