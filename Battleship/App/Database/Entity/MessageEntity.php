<?php

namespace Battleship\App\Database\Entity;

/**
 * Аттрибуты:
 * created_at,
 * content,
 * game_id,
 * player_id
 */
class MessageEntity extends AbstractEntity
{
    protected ?int $id;
    protected array $data;

    public function __construct($row)
    {
        $this->data = $row;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->data['id'];
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->data['created_at'];
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->data['content'];
    }

    /**
     * @return int
     */
    public function getGameId(): int
    {
        return $this->data['game_id'];
    }

    /**
     * @return int
     */
    public function getPlayerId(): int
    {
        return $this->data['player_id'];
    }
}