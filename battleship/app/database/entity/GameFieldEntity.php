<?php

class GameFieldEntity extends AbstractEntity
{
    protected ?int $id;
    protected int $gameId;
    protected int $playerId;

    public function __construct(array $row)
    {
        $this->id = $row['id'] ?? null;
        $this->gameId = $row['game_id'];
        $this->playerId = $row['player_id'];
    }

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