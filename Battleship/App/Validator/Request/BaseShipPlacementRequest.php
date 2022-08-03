<?php

namespace Battleship\App\Validator\Request;

use Battleship\App\Database\Model\GameModel;
use Battleship\App\Validator\Rule\IsCorrectGameStatus;
use Battleship\App\Validator\Rule\IsPlayerNotReady;
use JetBrains\PhpStorm\ArrayShape;

class BaseShipPlacementRequest extends BaseRequest
{
    #[ArrayShape([
        'gameId' => "int",
        'playerCode' => "string",
        'gameAndPlayer' => "array"
    ])] protected function prepareParams(array $params): array
    {
        return parent::prepareParams($params);
    }

    #[ArrayShape([
        'gameId' => "array",
        'playerCode' => "array",
        'gameAndPlayer' => "array"
    ])]
    protected function rules(): array
    {
        $preparedRules = parent::rules();
        $preparedRules['gameAndPlayer'] = array_merge(
            $preparedRules['gameAndPlayer'],
            [
                new IsCorrectGameStatus(GameModel::PLACE_SHIP_GAME_STATUS),
                new IsPlayerNotReady()
            ]
        );
        return $preparedRules;
    }

}