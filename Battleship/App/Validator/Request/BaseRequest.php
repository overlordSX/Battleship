<?php

namespace Battleship\App\Validator\Request;

use Battleship\App\Validator\Rule\IfWasErrorsStop;
use Battleship\App\Validator\Rule\IsGameExist;
use Battleship\App\Validator\Rule\IsGameWithPlayerExist;
use Battleship\App\Validator\Rule\IsPlayerExist;
use Battleship\App\Validator\Rule\IsPosInt;
use Battleship\App\Validator\Rule\IsStringRequired;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Проверит существование игры, игрока, и того что данный игрок участвует в этой игре
 */
class BaseRequest extends AbstractRequest
{
    #[ArrayShape([
        'gameId' => "int",
        'playerCode' => "string",
        'gameAndPlayer' => "array"
    ])]
    protected function prepareParams(array $params): array
    {
        $gameId = (int)$params['gameId'];
        $playerCode = (string)$params['playerCode'];

        return [
            'gameId' => $gameId,
            'playerCode' => $playerCode,
            'gameAndPlayer' => ['gameId' => $gameId, 'playerCode' => $playerCode]
        ];
    }

    #[ArrayShape([
        'gameId' => "array",
        'playerCode' => "array",
        'gameAndPlayer' => "array"
    ])]
    protected function rules(): array
    {
        return [
            'gameId' => [
                new IsPosInt(),
                new IsGameExist()
            ],
            'playerCode' => [
                new IfWasErrorsStop(),
                new IsStringRequired(),
                new IsPlayerExist()
            ],
            'gameAndPlayer' => [
                new IfWasErrorsStop(),
                new IsGameWithPlayerExist()
            ]
        ];
    }
}