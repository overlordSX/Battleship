<?php

namespace Battleship\App\Database\Entity;

class MessageEntity extends AbstractEntity
{
    protected ?int $id;
    protected int $createdAt;
    protected string $content;
    protected int $gameId;
    protected int $playerId;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return int
     */
    public function getGameId(): int
    {
        return $this->gameId;
    }

    /**
     * @return int
     */
    public function getPlayerId(): int
    {
        return $this->playerId;
    }
}