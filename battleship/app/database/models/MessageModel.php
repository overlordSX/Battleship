<?php

class MessageModel extends AbstractModel
{
    protected string $tableName = 'message';
    protected array $tableFields =
        [
            [
                'time',
                'content',
                'game_id',
                'player_id'
            ]
        ]
    ;


    public function query(): QueryBuilder
    {
        return (new QueryBuilder(MessageEntity::class))->from($this->tableName);
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