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
        return (new QueryBuilder())->from($this->tableName);
    }

    /**
     * в парамс должна быть струтура скобок [[1,2],[],...] => (1,2),(),...
     * @param array $params здесь должны быть параметры [[1,2,3],...] => (1,2,3),...
     * @return bool
     */
    public function insert(array $params, array $tableFields = []): bool
    {
        //var_dump($tableFields ? $tableFields : $this->tableFields);
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