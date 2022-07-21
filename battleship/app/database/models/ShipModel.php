<?php

class ShipModel extends AbstractModel
{
    //TODO возможно кораблям нужно название корабля, типо: name => "4-1"
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
        return (new QueryBuilder())->from($this->tableName);
    }

    /**
     * в парамс должна быть струтура скобок [[1,2],[],...] => (1,2),(),...
     * @param array $params здесь должны быть параметры [[1,2,3],...] => (1,2,3),...
     * @return bool
     */
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