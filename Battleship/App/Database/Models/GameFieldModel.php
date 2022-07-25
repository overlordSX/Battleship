<?php

/**
 * Аттрибуты:
 * game_id,
 * player_id
 */
class GameFieldModel extends AbstractModel
{
    protected string $tableName = 'game_field';
    protected string $entityClassName = GameFieldEntity::class;

}