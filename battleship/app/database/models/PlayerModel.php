<?php

class PlayerModel extends AbstractModel
{
    //можно table name
    protected string $tableName = 'player';
    protected array $tableFields =
        [
            [
                'code'
            ]
        ];

    protected string $lastOperation = '';


    public function query(): QueryBuilder
    {
        return (new QueryBuilder(PlayerEntity::class))->from($this->tableName);
    }

    /**
     * в парамс должна быть струтура скобок [[1,2],[],...] => (1,2),(),...
     * @param array $params здесь должны быть параметры [[1,2,3],...] => (1,2,3),...
     * @return bool
     */
    public function insert(array $params, array $tableFields = []): bool
    {
        //var_dump($tableFields ? $tableFields : $this->tableFields);
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

    //TODO как такое сделать? чтобы мне у меня старый объект удалялся, и приходил новый
    public function clear(): self
    {
        /*match ($this->lastOperation) {
            'query' => function() { return (new QueryBuilder())->from($this->tableName); },
            'insert' => function() { return (new QueryBuilder())->insert($this->tableName, $params)}
        }*/
        return new self;
    }
}