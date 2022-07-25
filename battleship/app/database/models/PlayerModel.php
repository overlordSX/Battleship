<?php

/**
 * Аттрибуты:
 * code
 */
class PlayerModel extends AbstractModel
{
    protected string $tableName = 'player';
    protected string $entityClassName = PlayerEntity::class;
}