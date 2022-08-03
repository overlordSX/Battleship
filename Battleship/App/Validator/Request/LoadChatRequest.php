<?php

namespace Battleship\App\Validator\Request;

use Battleship\App\Validator\Rule\IfWasErrorsStop;
use Battleship\App\Validator\Rule\IsCorrectLastTime;
use JetBrains\PhpStorm\ArrayShape;

class LoadChatRequest extends BaseRequest
{

    #[ArrayShape([
        'gameId' => "int",
        'playerCode' => "string",
        'gameAndPlayer' => "array",
        'lastTime' => "int|null"
    ])]
    protected function prepareParams(array $params): array
    {
        $preparedParams = parent::prepareParams($params);
        $preparedParams['lastTime'] = isset($_GET['lastTime']) ? (int)$_GET['lastTime'] : null;
        return $preparedParams;
    }

    #[ArrayShape([
        'gameId' => "array",
        'playerCode' => "array",
        'gameAndPlayer' => "array",
        'lastTime' => "array"
    ])]
    protected function rules(): array
    {
        $preparedRules = parent::rules();
        $preparedRules['lastTime'] = [new IfWasErrorsStop(), new IsCorrectLastTime()];

        return $preparedRules;
    }

}