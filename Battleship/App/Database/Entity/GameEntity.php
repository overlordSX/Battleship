<?php

namespace Battleship\App\Database\Entity;

/**
 * есть следующие поля:
 * id, turn, game_status_id, first_player_id, second_player_id
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
     * @return bool
     */
    public function getTurn(): bool
    {
        return $this->data['turn'];
    }

    /**
     * @return int
     */
    public function getFirstPlayerId(): mixed
    {
        return $this->data['first_player_id'];
    }

    /**
     * @return int
     */
    public function getSecondPlayerId(): mixed
    {
        return $this->data['second_player_id'];
    }

}