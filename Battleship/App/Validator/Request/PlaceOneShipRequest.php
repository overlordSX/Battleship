<?php

namespace Battleship\App\Validator\Request;

use Battleship\App\Database\Model\GameFieldModel;
use Battleship\App\Database\Model\GameModel;
use Battleship\App\Database\Model\PlayerModel;
use Battleship\App\Database\Model\ShipPlacementModel;
use Battleship\App\Validator\Rule\IfWasErrorsStop;
use Battleship\App\Validator\Rule\IsCorrectOrientation;
use Battleship\App\Validator\Rule\IsNoShipsIntersection;
use Battleship\App\Validator\Rule\IsInScope;
use Battleship\App\Validator\Rule\IsInt;
use Battleship\App\Validator\Rule\IsRequired;
use Battleship\App\Validator\Rule\IsShipExist;
use Battleship\App\Validator\Rule\IsString;
use JetBrains\PhpStorm\ArrayShape;


class PlaceOneShipRequest extends AbstractRequest
{

    protected int $xSizeBound;
    protected int $ySizeBound;

    /**
     * @throws \Exception
     */
    #[ArrayShape([
        'x' => "array",
        'y' => "array",
        'shipName' => "array",
        'orientation' => "array",
        'gameAndPlayerAndShip' => "array"
    ])]
    protected function prepareParams(array $params): array
    {
        $gameId = $params['gameAndPlayer']['gameId'];
        $playerCode = $params['gameAndPlayer']['playerCode'];
        $ship = $params['oneShip'];

        $game = (new GameModel())->getGameById($gameId);
        $playerModel = new PlayerModel();
        $player = $playerModel->getPlayerByCode($playerCode);
        $gameField = (new GameFieldModel())->getByGameAndPlayer($game->getId(), $player->getId());
        $shipPlacementModel = new ShipPlacementModel();
        $shipPlacementModel->fillFieldAndUsedPlaces($gameField->getId());
        $field = $shipPlacementModel->getField();

        $isHorizontal = (string)$ship['orientation'] === 'horizontal';
        $this->xSizeBound = ShipPlacementModel::FIELD_SIZE + 1
            - ($isHorizontal ? (int)substr($ship['ship'], 0, 1) : 0);
        $this->ySizeBound = ShipPlacementModel::FIELD_SIZE + 1
            - ($isHorizontal ? 0 : (int)substr($ship['ship'], 0, 1));

        $preparedParams['x'] = (int)$ship['x'];
        $preparedParams['y'] = (int)$ship['y'];
        $preparedParams['shipName'] = (string)$ship['ship'];
        $preparedParams['orientation'] = (string)$ship['orientation'];
        $preparedParams['gameAndPlayerAndShip'] = ['field' => $field, 'ship' => $ship];

        return $preparedParams;
    }

    #[ArrayShape([
        'x' => "array",
        'y' => "array",
        'shipName' => "array",
        'orientation' => "array",
        'gameAndPlayerAndShip' => "\Battleship\App\Validator\Rule\IsCorrectShipPlacement[]"
    ])]
    protected function rules(): array
    {
        return [
            'x' => [
                new IfWasErrorsStop(),
                new IsRequired('Координата X'),
                new IsInt('Координата X'),
                new IsInScope(0, $this->xSizeBound)],
            'y' => [
                new IfWasErrorsStop(),
                new IsRequired('Координата Y'),
                new IsInt('Координата Y'),
                new IsInScope(0, $this->ySizeBound)],
            'shipName' => [
                new IfWasErrorsStop(),
                new IsRequired('Название корабля'),
                new IsString(), new IsShipExist()],
            'orientation' => [
                new IfWasErrorsStop(),
                new IsRequired('Ориентация корабля'),
                new IsString(), new IsCorrectOrientation()],
            'gameAndPlayerAndShip' => [
                new IfWasErrorsStop(),
                new IsNoShipsIntersection()]
        ];
    }
}