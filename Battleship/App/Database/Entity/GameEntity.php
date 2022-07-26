<?php

namespace Battleship\App\Database\Entity;

/**
 * есть следующие поля:
 * id, turn, game_status_id, first_player_id, second_player_id, first_ready, second_ready
 */
class GameEntity extends AbstractEntity
{
    protected ?int $id;
    protected bool $turn;
    protected int $gameStatusId;
    protected int $firstPlayerId;
    protected int $secondPlayerId;

    protected array $data;


    public function __construct(array $row)
    {
        //TODO откуда и что выкидывать по поводу того что кто либо хочет получить не существующую игру
        // если будет несуществующий id, то придет false из pdo

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