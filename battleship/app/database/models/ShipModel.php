<?php

class ShipModel extends AbstractModel
{

    protected string $tableName = 'ship';
    protected array $tableFields =
        [
            [
                'size',
                'quantity'
            ]
        ];


    public function query(): QueryBuilder
    {
        return (new QueryBuilder(ShipEntity::class))->from($this->tableName);
    }


    public function insert(array $params, array $tableFields = []): bool
    {
        //TODO скорее всегго нужно сюда добавить вызов clear()
        return (new QueryBuilder())->insert($this->tableName, $params);
    }

    public function update(): QueryBuilder
    {
        return new QueryBuilder();
    }

    public function delete(): QueryBuilder
    {
        return new QueryBuilder();
    }


    public function clear(): self
    {
        return new self;
    }
}