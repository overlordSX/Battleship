<?php

namespace Battleship\App\Validator\Request;

use Battleship\App\Database\Model\GameModel;
use Battleship\App\Database\Model\ShipPlacementModel;
use Battleship\App\Validator\Rule\IfWasErrorsStop;
use Battleship\App\Validator\Rule\IsCorrectGameStatus;
use Battleship\App\Validator\Rule\IsInScope;
use Battleship\App\Validator\Rule\IsInt;
use Battleship\App\Validator\Rule\IsRequired;
use Battleship\App\Validator\Rule\IsShotNotExist;
use Battleship\App\Validator\Rule\IsThisPlayerTurn;
use JetBrains\PhpStorm\ArrayShape;

class MakeShotRequest extends BaseRequest
{
    #[ArrayShape([
        'gameId' => "int",
        'playerCode' => "string",
        'gameAndPlayer' => "array",
        'GameAndPlayerAndXY' => "array",
        'y' => "int|null",
        'x' => "int|null"
    ])]
    protected function prepareParams(array $params): array
    {
        $x = isset($_POST['x']) ? (int)htmlspecialchars($_POST['x'], ENT_QUOTES) : null;
        $y = isset($_POST['y']) ? (int)htmlspecialchars($_POST['y'], ENT_QUOTES) : null;

        $preparedParams = parent::prepareParams($params);
        $preparedParams['x'] = $x;
        $preparedParams['y'] = $y;
        $preparedParams['GameAndPlayerAndXY'] = [
            'gameId' => $params['gameId'],
            'playerCode' => $params['playerCode'],
            'x' => $x,
            'y' => $y
        ];

        return $preparedParams;
    }

    #[ArrayShape([
        'gameId' => "array",
        'playerCode' => "array",
        'gameAndPlayer' => "array",
        'GameAndPlayerAndXY' => "array",
        'y' => "array",
        'x' => "array"
    ])]
    protected function rules(): array
    {
        $preparedRules = parent::rules();
        $preparedRules['gameAndPlayer'] = array_merge(
            $preparedRules['gameAndPlayer'],
            [
                new IsCorrectGameStatus(GameModel::BATTLE_GAME_STATUS),
                new IsThisPlayerTurn()
            ]);
        $preparedRules['x'] = [
            new IfWasErrorsStop(),
            new IsRequired('Координата X'),
            new IsInt('Координата X'),
            new IsInScope(0, ShipPlacementModel::FIELD_EDGE_COORDINATE)
        ];
        $preparedRules['y'] = [
            new IfWasErrorsStop(),
            new IsRequired('Координата Y'),
            new IsInt('Координата Y'),
            new IsInScope(0, ShipPlacementModel::FIELD_EDGE_COORDINATE)
        ];
        $preparedRules['GameAndPlayerAndXY'] = [
            new IfWasErrorsStop(),
            new IsShotNotExist()
        ];

        return $preparedRules;
    }
}