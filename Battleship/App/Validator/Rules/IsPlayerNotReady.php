<?php

namespace Battleship\App\Validator\Rule;

use Battleship\App\Database\Model\GameModel;
use Battleship\App\Database\Model\PlayerModel;
use Battleship\App\Validator\RuleInterface;

class IsPlayerNotReady implements RuleInterface
{
    public function pass($value): bool
    {
        $gameId = $value['gameId'];
        $playerCode = $value['playerCode'];

        $gameModel = new GameModel();
        $playerModel = new PlayerModel();

        $player = $playerModel->getPlayerByCode($playerCode);
        $game = $gameModel->getGameById($gameId);

        return !$playerModel->isCurrentReady($game, $player);
    }

    public function message(): string
    {
        return 'Вы уже установили свою готовность, ожидайте готовности противника';
    }
}