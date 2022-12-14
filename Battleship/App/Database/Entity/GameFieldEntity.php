<?php

namespace Battleship\App\Database\Entity;

class GameFieldEntity extends AbstractEntity
{
    protected array $data;

    public function __construct(array $row)
    {
        $this->data = $row;
    }

    /**
     * @return int
     */
    public function getId(): int
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