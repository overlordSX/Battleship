<?php

namespace Battleship\App\Validator\Rule;

use Battleship\App\Database\Model\GameFieldModel;
use Battleship\App\Database\Model\GameModel;
use Battleship\App\Database\Model\PlayerModel;
use Battleship\App\Database\Model\ShipPlacementModel;
use Battleship\App\Validator\RuleInterface;

class IsShipOnField implements RuleInterface
{

    /** @throws \Exception */
    public function pass($value): bool
    {
        $gameId = $value['gameId'];
        $playerCode = $value['playerCode'];
        $shipName = $value['shipName'];

        $game = (new GameModel())->getGameById($gameId);

        $playerModel = new PlayerModel();
        $player = $playerModel->getPlayerByCode($playerCode);

        $gameField = (new GameFieldModel())->getByGameAndPlayer($game->getId(), $player->getId());

        $shipPlacement = new ShipPlacementModel();
        $placedShips = $shipPlacement->getPlacedShipsList($gameField->getId());

        foreach ($placedShips as $ship) {
            if ($ship->getCustom('name') === $shipName) {
                return true;
            }
        }
        return false;
    }

    public function message(): string
    {
        return 'Данный корабль не находится на поле';
    }
}