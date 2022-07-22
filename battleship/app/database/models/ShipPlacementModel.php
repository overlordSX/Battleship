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

    public function clear(): self
    {
        return new self;
    }
}