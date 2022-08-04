<?php

namespace Battleship\App\Validator\Rule;

use Battleship\App\Database\Model\GameModel;
use Battleship\App\Database\Model\GameStatusModel;
use Battleship\App\Validator\RuleInterface;
use Exception;

class IsCorrectGameStatus implements RuleInterface
{
    public function __construct(protected int $gameStatus)
    {
    }

    /** @throws Exception */
    public function pass($value): bool
    {
        $gameId = $value['gameId'];

        $gameModel = GameModel::getInstance();
        $gameModel->setGame($gameId);
        $game = $gameModel->getGame();

        return $game->getGameStatusId() === $this->gameStatus;
    }

    /**
     * @throws Exception
     */
    public function message(): string
    {
        $gameStatusModel = new GameStatusModel();
        $status = $gameStatusModel->getStatus($this->gameStatus);
        return 'Этап: ' . $status->getDescription() . '  - уже прошел';
    }
}