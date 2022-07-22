<?php

class PlayerModel extends AbstractModel
{
    protected string $tableName = 'player';
    protected array $tableFields =
        [
            [
                'code'
            ]
        ];



    public function query(): QueryBuilder
    {
        return (new QueryBuilder(PlayerEntity::class))->from($this->tableName);
    }


    public function insert(array $params, array $tableFields = []): bool
    {
        /*return (new QueryBuilder())
            ->insertMulti(
                $this->tableName,
                $tableFields ?: $this->tableFields,
                $params//там метод генерации placeholder (?,?,?),....,
            );*/
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