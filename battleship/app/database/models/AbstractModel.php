<?php

abstract class AbstractModel
{
    protected string $tableName;
    protected array $tableFields;

    /**
     * @return QueryBuilder ->from($tableName)
     */
    abstract public function query(): QueryBuilder;

    /**
     * @param array $params
     * @return bool успешно или нет
     */
    abstract public function insert(array $params): bool;

    /**
     * @param array $attribute
     * @param array $value
     * @param array $updateValue
     * @return QueryBuilder ->update($tableName)
     */
    abstract public function update(/*array $attribute, array $value, array $updateValue*/): QueryBuilder;

    /**
     * @param array $attribute
     * @param array $value
     * @return QueryBuilder ->delete($tableName)
     */
    abstract public function delete(/*array $attribute, array $value*/): QueryBuilder;

    abstract public function clear(): self;
}