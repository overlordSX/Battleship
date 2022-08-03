<?php

namespace Battleship\App\Validator\Request;

use Battleship\App\Database\Model\GameModel;
use Battleship\App\Validator\Rule\IfWasErrorsStop;
use Battleship\App\Validator\Rule\IsCorrectGameStatus;
use Battleship\App\Validator\Rule\IsCorrectShipName;
use Battleship\App\Validator\Rule\IsPlayerNotReady;
use Battleship\App\Validator\Rule\IsShipExist;
use Battleship\App\Validator\Rule\IsShipOnField;
use JetBrains\PhpStorm\ArrayShape;

class UnsetShipRequest extends BaseRequest
{
    #[ArrayShape([
        'gameId' => "int",
        'playerCode' => "string",
        'gameAndPlayer' => "array"
    ])]
    protected function prepareParams(array $params): array
    {
        $shipName = substr($_POST['ship'] ?? null, 0, 3);

        $preparedParams = parent::prepareParams($params);
        $preparedParams['shipName'] = $shipName;
        $preparedParams['gameIdAndPlayerCodeAndShip'] = array_merge(
            $preparedParams['gameAndPlayer'],
            ['shipName' => $shipName]
        );
        return $preparedParams;
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
        $preparedRules['shipName'] = [new IfWasErrorsStop(), new IsCorrectShipName(), new IsShipExist()];
        $preparedRules['gameIdAndPlayerCodeAndShip'] = [new IfWasErrorsStop(), new IsShipOnField()];

        return $preparedRules;
    }
}