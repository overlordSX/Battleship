<?php

namespace Battleship\App\Database\Model;

use Battleship\App\Database\QueryBuilder;
use Exception;

abstract class AbstractModel
{
    protected string $tableName;
    protected string $entityClassName;

    /** @throws Exception */
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
     * ['attr' => value]
     * @param array $params
     * @return bool успешно или нет
     * @throws Exception
     */
    public function insert(array $params): bool
    {
        return $this->getQueryBuilder()->insert($this->tableName, $params);
    }

    /**
     * @param array $conditions [['attr' => ['cond' => value]], ...]
     * @param array $sets [['attr' => newVal], ...]
     * @return bool успешно или нет
     * @throws Exception
     */
    public function update(array $conditions, array $sets): bool
    {
        return $this->getQueryBuilder()->update($this->tableName, $conditions, $sets);
    }

    /**
     * ['attr' => ['cond' => value]
     * @param string $attribute
     * @param string $condition
     * @param int|string $value
     * @return bool
     * @throws Exception
     */
    public function delete(string $attribute, string $condition, int|string $value): bool
    {
        return $this->getQueryBuilder()->delete($this->tableName, $attribute, $condition, $value);
    }
}