<?php

/**
 * Аттрибуты:
 * invite_code,
 * turn,
 * game_status_id,
 * player_id
 */
class GameModel extends AbstractModel
{
    protected string $tableName = 'game';
    protected string $entityClassName = GameEntity::class;
}