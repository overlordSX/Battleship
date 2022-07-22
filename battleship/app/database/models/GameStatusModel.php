<?php

class GameStatusModel extends AbstractModel
{

    protected string $tableName = 'game_status';
    protected array $tableFields =
        [
            [
                'status',
                'description'
            ]
        ]
    ;


    public function query(): QueryBuilder
    {
        return (new QueryBuilder(GameStatusEntity::class))->from($this->tableName);
    }

    public function insert(array $params, array $tableFields = []): bool
    {
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

    public function clear(): AbstractModel
    {
        return new self;
    }


}