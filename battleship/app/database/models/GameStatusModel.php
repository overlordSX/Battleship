<?php

/**
 * Аттрибуты:
 * status,
 * description
 */
class GameStatusModel extends AbstractModel
{

    protected string $tableName = 'game_status';
    protected string $entityClassName = GameStatusEntity::class;

}