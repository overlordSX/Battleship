<?php

namespace Battleship\App\Database\Entity;

use Battleship\App\Database\Model\PlayerModel;

/**
 * есть следующие поля:
 * id, turn, game_status_id, first_player_id, second_player_id, first_ready, second_ready
 */
class GameEntity extends AbstractEntity
{
    protected ?int $id;

    protected array $data;

    protected PlayerEntity $firstPlayer;
    protected PlayerEntity $secondPlayer;



    public function __construct(array $row)
    {
        //TODO откуда и что выкидывать по поводу того что кто либо хочет получить не существующую игру
        // если будет несуществующий id, то придет false из pdo

        $this->data = $row;

    }

    /**
     * @return PlayerEntity
     * @throws \Exception
     */
    public function getFirstPlayer(): PlayerEntity
    {
        if (!isset($this->firstPlayer)) {
            $this->firstPlayer = (new PlayerModel())->getPlayerById($this->data['first_player_id']);
        }

        return $this->firstPlayer;
    }

    /**
     * @return PlayerEntity
     * @throws \Exception
     */
    public function getSecondPlayer(): PlayerEntity
    {
        if (!isset($this->secondPlayer)) {
            $this->secondPlayer = (new PlayerModel())->getPlayerById($this->data['first_player_id']);
        }

        return $this->secondPlayer;
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