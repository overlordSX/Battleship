<?php

namespace Battleship\App\Validator\Request;

use Battleship\App\Controllers\Util\JsonUtil;
use Battleship\App\Helpers\PrepareShipSize;
use Battleship\App\Helpers\ShipOrientationHelper;
use Exception;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Обертка разруливающая запрос на три варианта валидации
 */
class PlaceShipRequest extends AbstractRequest
{

    /** @throws Exception */
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
            $shipName = (string)$ship['ship'];
            $shipSize = PrepareShipSize::prepare($shipName);
            $orientation = (string)$ship['orientation'];
            $isHorizontal = ShipOrientationHelper::isHorizontalFromString($orientation);
            $shipX = (int)$ship['x'];
            $shipY = (int)$ship['y'];

            $placeOneShipRequest->validate([
                'orientation' => $orientation,
                'isHorizontal' => $isHorizontal,
                'shipSize' => $shipSize,
                'shipName' => $shipName,
                'shipX' => $shipX,
                'shipY' => $shipY
            ]);
            $requestAnswer = $placeOneShipRequest->answer();

            if ($requestAnswer) {
                JsonUtil::makeAnswer($requestAnswer);
            }

            $placeOneShipRequest->setShipToField($shipX, $shipY, $isHorizontal, $shipSize, $shipName);
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
                'ship' => $_POST['ship'],
                'orientation' => (string)$_POST['orientation']
            ]];
        }
        return $shipsQue;
    }
}