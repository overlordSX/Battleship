<?php

abstract class AbstractModel
{
    protected string $tableName;
    protected string $entityClassName;

    public function getQueryBuilder()
    {
        return new QueryBuilder();
    }

    /**
     * @return QueryBuilder ->from($tableName)
     */
    public function query(): QueryBuilder
    {
        return (new QueryBuilder($this->entityClassName))->from($this->tableName);
    }

    /**
     * @param array $params
     * @return bool успешно или нет
     */
    public function insert(array $params): bool
    {
        return (new QueryBuilder($this->entityClassName))->insert($this->tableName, $params);
    }

    /**
     * @param array $attribute
     * @param array $oldValue
     * @param array $newValue
     * @return bool успешно или нет
     */
    public function update(string $attribute, mixed $oldValue, mixed $newValue): bool
    {
        return (new QueryBuilder($this->entityClassName))->update($this->tableName, $attribute, $oldValue, $newValue);
    }

    public function delete(string $attribute, mixed $value): bool
    {
        return (new QueryBuilder($this->entityClassName))->delete($this->tableName, $attribute, $value);
    }
}