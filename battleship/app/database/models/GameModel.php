<?php

class GameModel extends AbstractModel
{
    protected string $tableName = 'game';
    protected array $tableFields =
        [
            [
                'invite_code',
                'turn',
                'game_status_id',
                'player_id'
            ]
        ];



    public function query(): QueryBuilder
    {
        return (new QueryBuilder(GameEntity::class))->from($this->tableName);
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

    /**
     * @return $this новый объект GameModel
     */
    public function clear(): self
    {
        return new self;
    }
}