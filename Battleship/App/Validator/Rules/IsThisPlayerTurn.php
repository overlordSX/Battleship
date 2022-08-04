<?php

namespace Battleship\App\Validator\Rule;

use Battleship\App\Database\Model\GameModel;
use Battleship\App\Database\Model\PlayerModel;
use Battleship\App\Validator\RuleInterface;
use Exception;

class IsThisPlayerTurn implements RuleInterface
{

    /** @throws Exception */
    public function pass($value): bool
    {
        $gameId = $value['gameId'];
        $playerCode = $value['playerCode'];

        $playerModel = new PlayerModel();
        $game = (GameModel::getInstance($gameId))->getGame();
        $player = $playerModel->getPlayerByCode($playerCode);

        return $playerModel->isMyTurn($game, $player);
    }

    public function message(): string
    {
        return 'Дождитесь своей очереди';
    }
}