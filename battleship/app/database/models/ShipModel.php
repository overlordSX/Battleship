<?php

/**
 * Аттрибуты:
 * size,
 * quantity
 */
class ShipModel extends AbstractModel
{

    protected string $tableName = 'ship';
    protected string $entityClassName = ShipEntity::class;

}