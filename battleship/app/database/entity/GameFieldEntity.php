<?php

class GameFieldEntity extends AbstractEntity
{
    protected ?int $id;

    protected array $data;

    public function __construct(array $row)
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