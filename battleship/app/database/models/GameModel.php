<?php

class GameModel extends AbstractModel
{
    //можно table name
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

    protected string $lastOperation = '';

    //TODO еще возможно должна быть переменная в которой хранится QueryBuilder
    // или почему я его новый отдаю постоянно :?

    public function query(): QueryBuilder
    {
        return (new QueryBuilder(GameEntity::class))->from($this->tableName);
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

    /**
     * @return $this новый объект GameModel
     */
    public function clear(): self
    {
        /*match ($this->lastOperation) {
            'query' => function() { return (new QueryBuilder())->from($this->tableName); },
            'insert' => function() { return (new QueryBuilder())->insert($this->tableName, $params)}
        }*/
        return new self;
    }
}