<?php

class ShipPlacementModel extends AbstractModel
{

    //TODO возможно кораблям нужно название корабля, типо: name => "4-1"
    protected string $tableName = 'ship_placement';
    protected array $tableFields =
        [
            [
                'coordinate_x',
                'coordinate_y',
                'orientation',
                'ship_id',
                'game_field_id'
            ]
        ];


    public function query(): QueryBuilder
    {
        return (new QueryBuilder(ShipPlacementEntity::class))->from($this->tableName);
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

    public function clear(): self
    {
        return new self;
    }
}