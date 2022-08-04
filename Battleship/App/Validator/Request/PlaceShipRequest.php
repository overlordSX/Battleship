<?php

namespace Battleship\App\Validator\Request;

use Battleship\App\Controllers\Util\JsonUtil;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Обертка разруливающая запрос на три варианта валидации
 */
class PlaceShipRequest extends AbstractRequest
{

    /** @throws \Exception */
    #[ArrayShape([
        'gameId' => "int",
        'playerCode' => "string",
        'gameAndPlayer' => "array"
    ])]
    protected function prepareParams(array $params): array
    {
        $baseRequest = new BaseShipPlacementRequest();
        $baseRequest->validate($params);
        $requestAnswer = $baseRequest->answer();
        if ($requestAnswer) {
            JsonUtil::makeAnswer($requestAnswer);
        }

        $shipsQue = $this->prepareShipsQue();

        if ($this->isRemoveShip()) {
            $removeShipRequest = new RemoveShipRequest();
            $removeShipRequest->validate($params);
            $requestAnswer = $removeShipRequest->answer();

            if ($requestAnswer) {
                JsonUtil::makeAnswer($requestAnswer);
            }

            return [];
        }

        if (empty($shipsQue)) {
            JsonUtil::makeAnswer(['success' => false, 'message' => 'Передано недостаточно параметров']);
        }

        $gameId = $params['gameId'];
        $playerCode = $params['playerCode'];
        $placeOneShipRequest = new PlaceOneShipRequest($gameId, $playerCode);

        foreach ($shipsQue as $ship) {
            $placeOneShipRequest->validate(['oneShip' => $ship]);
            $requestAnswer = $placeOneShipRequest->answer();

            if ($requestAnswer) {
                JsonUtil::makeAnswer($requestAnswer);
            }

            $placeOneShipRequest->setShipToField($ship);
        }

        return [];
    }

    #[ArrayShape([
        'gameId' => "array",
        'playerCode' => "array",
        'gameAndPlayer' => "array"
    ])]
    protected function rules(): array
    {
        return [];
    }

    protected function isRemoveShip(): bool
    {
        return isset($_POST['ship'])
            && !isset($_POST['x'])
            && !isset($_POST['y'])
            && !isset($_POST['orientation'])
            && !isset($_POST['ships']);

    }

    /**
     * подготавливает очередь кораблей, в зависимости от post запроса
     * @return array
     */
    protected function prepareShipsQue(): array
    {
        $isArray = isset($_POST['ships'])
            && !isset($_POST['ship'])
            && !isset($_POST['x'])
            && !isset($_POST['y'])
            && !isset($_POST['orientation']);
        $isOneShip = isset($_POST['ship'])
            && isset($_POST['x'])
            && isset($_POST['y'])
            && isset($_POST['orientation'])
            && !isset($_POST['ships']);

        $shipsQue = [];

        if ($isArray) {
            $shipsQue = $_POST['ships'];
        } elseif ($isOneShip) {
            $shipsQue = [[
                'x' => (int)$_POST['x'],
                'y' => (int)$_POST['y'],
                'ship' => substr($_POST['ship'], 0, 3),
                'orientation' => (string)$_POST['orientation']
            ]];
        }
        return $shipsQue;
    }
}