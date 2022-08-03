<?php

namespace Battleship\App\Validator\Rule;

use Battleship\App\Database\Model\GameFieldModel;
use Battleship\App\Database\Model\GameModel;
use Battleship\App\Database\Model\PlayerModel;
use Battleship\App\Database\Model\ShotModel;
use Battleship\App\Validator\RuleInterface;

class IsShotNotExist implements RuleInterface
{
    /** @throws \Exception */
    public function pass($value): bool
    {
        $gameId = $value['gameId'];
        $playerCode = $value['playerCode'];
        $x = $value['x'];
        $y = $value['y'];

        $game = (new GameModel())->getGameById($gameId);
        $playerModel = new PlayerModel();
        $player = $playerModel->getPlayerByCode($playerCode);
        $enemyPlayer = $playerModel->getEnemyPlayer($game, $player);

        $gameField = (new GameFieldModel())->getByGameAndPlayer($gameId, $enemyPlayer->getId());


        $shotModel = new ShotModel();
        return !$shotModel->query()
            ->where('game_field_id', '=', $gameField->getId())
            ->where('coordinate_x', '=', $x)
            ->where('coordinate_y', '=', $y)
            ->fetchCount();
    }

    public function message(): string
    {
        return 'Выстрел в эту точку уже был';
    }
}