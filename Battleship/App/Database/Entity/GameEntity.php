<?php

namespace Battleship\App\Database\Entity;

use Battleship\App\Database\Model\PlayerModel;

/**
 * есть следующие поля:
 * id, turn, game_status_id, first_player_id, second_player_id, first_ready, second_ready
 */
class GameEntity extends AbstractEntity
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
    public function getGameStatusId(): int
    {
        return $this->data['game_status_id'];
    }

    /**
     * false - ход первого
     * true - ход второго
     * @return bool
     */
    public function getTurn(): bool
    {
        return $this->data['turn'];
    }

    public function isFirstPlayerTurn(): bool
    {
        return !$this->data['turn'];
    }

    public function isSecondPlayerTurn(): bool
    {
        return $this->data['turn'];
    }

    /**
     * @return int
     */
    public function getFirstPlayerId(): int
    {
        return $this->data['first_player_id'];
    }

    /**
     * @return int
     */
    public function getSecondPlayerId(): int
    {
        return $this->data['second_player_id'];
    }

    /**
     * @return bool
     */
    public function isFirstReady(): bool
    {
        return $this->data['first_ready'];
    }

    /**
     * @return bool
     */
    public function isSecondReady(): bool
    {
        return $this->data['second_ready'];
    }
}