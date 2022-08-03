<?php

namespace Battleship\App\Validator\Request;

use Battleship\App\Validator\Rule\IfWasErrorsStop;
use Battleship\App\Validator\Rule\IsRequired;
use Battleship\App\Validator\Rule\IsString;
use JetBrains\PhpStorm\ArrayShape;

class SendMessageRequest extends BaseRequest
{

    #[ArrayShape([
        'gameId' => "int",
        'playerCode' => "string",
        'gameAndPlayer' => "array",
        'message' => "array"
    ])]
    protected function prepareParams(array $params): array
    {
        $preparedParams = parent::prepareParams($params);
        $preparedParams['message'] = isset($_POST['message'])
            ? htmlspecialchars(trim((string)$_POST['message']))
            : null;
        return $preparedParams;
    }

    #[ArrayShape([
        'gameId' => "array",
        'playerCode' => "array",
        'gameAndPlayer' => "array",
        'message' => "null|string"
    ])]
    protected function rules(): array
    {
        $preparedRules = parent::rules();
        $preparedRules['message'] = [
            new IfWasErrorsStop(),
            new IsRequired('Текст сообщения'),
            new IsString()
        ];
        return $preparedRules;
    }

}