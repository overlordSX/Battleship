<?php

/**
 * Аттрибуты:
 * time,
 * content,
 * game_id,
 * player_id
 */
class MessageModel extends AbstractModel
{
    protected string $tableName = 'message';
    protected string $entityClassName = MessageEntity::class;

}