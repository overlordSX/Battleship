<?php

namespace Battleship\App\Validator\Request;

use Battleship\App\Validator\Rule\IfWasErrorsStop;
use Battleship\App\Validator\Rule\IsCorrectShipName;
use Battleship\App\Validator\Rule\IsShipExist;
use Battleship\App\Validator\Rule\IsShipOnField;
use JetBrains\PhpStorm\ArrayShape;

class RemoveShipRequest extends AbstractRequest
{
    #[ArrayShape([
        'gameId' => "int",
        'playerCode' => "string",
        'gameAndPlayer' => "array"
    ])]
    protected function prepareParams(array $params): array
    {
        $shipName = $_POST['ship'] ?? null;

        $preparedParams['shipName'] = $shipName;
        $preparedParams['gameIdAndPlayerCodeAndShip'] = array_merge(
            $params,
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
        $preparedRules['shipName'] = [new IfWasErrorsStop(), new IsCorrectShipName(), new IsShipExist()];
        $preparedRules['gameIdAndPlayerCodeAndShip'] = [new IfWasErrorsStop(), new IsShipOnField()];

        return $preparedRules;
    }
}