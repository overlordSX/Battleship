<?php

namespace Battleship\App\Validator\Rule;

use Battleship\App\Database\Model\GameModel;
use Battleship\App\Database\Model\PlayerModel;
use Battleship\App\Validator\RuleInterface;

class IsThisPlayerTurn implements RuleInterface
{

    /** @throws \Exception */
    public function pass($value): bool
    {
        $gameId = $value['gameId'];
        $playerCode = $value['playerCode'];

        $playerModel = new PlayerModel();
        $game = (new GameModel())->getGameById($gameId);
        $player = $playerModel->getPlayerByCode($playerCode);

        return $playerModel->isMyTurn($game, $player);
    }

    public function message(): string
    {
        return 'Дождитесь своей очереди';
    }
}