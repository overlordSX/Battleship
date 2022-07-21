<?php

class GameEntity extends AbstractEntity
{
    protected ?int $id;
    protected string $inviteCode;
    protected bool $turn;
    protected int $gameStatusId;
    protected int $playerId;


    //TODO либо сеттеры геттеры, либо билдер, либо конструктор из ассоциативной строки

    public function __construct(array $row)
    {
        $this->id = $row['id'] ?? null;
        $this->inviteCode = $row['invite_code'];
        $this->turn = $row['turn'];
        $this->gameStatusId = $row['game_status_id'];
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
     * @return string
     */
    public function getInviteCode(): string
    {
        return $this->inviteCode;
    }

    /**
     * @return bool
     */
    public function isTurn(): bool
    {
        return $this->turn;
    }

    /**
     * @return int
     */
    public function getGameStatusId(): int
    {
        return $this->gameStatusId;
    }

    /**
     * @return int
     */
    public function getPlayerId(): int
    {
        return $this->playerId;
    }
}