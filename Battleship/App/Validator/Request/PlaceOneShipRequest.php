<?php

namespace Battleship\App\Validator\Request;

use Battleship\App\Database\Model\GameFieldModel;
use Battleship\App\Database\Model\GameModel;
use Battleship\App\Database\Model\PlayerModel;
use Battleship\App\Database\Model\ShipPlacementModel;
use Battleship\App\Database\Model\ShotModel;
use Battleship\App\Validator\Rule\IfWasErrorsStop;
use Battleship\App\Validator\Rule\IsCorrectOrientation;
use Battleship\App\Validator\Rule\IsNoShipsIntersection;
use Battleship\App\Validator\Rule\IsInScope;
use Battleship\App\Validator\Rule\IsInt;
use Battleship\App\Validator\Rule\IsRequired;
use Battleship\App\Validator\Rule\IsShipExist;
use Battleship\App\Validator\Rule\IsStringRequired;
use JetBrains\PhpStorm\ArrayShape;


class PlaceOneShipRequest extends AbstractRequest
{
    protected int $xSizeBound;
    protected int $ySizeBound;
    protected int $gameId;
    protected string $playerCode;
    protected array $field;

    /** @throws \Exception */
    public function __construct(int $gameId, string $playerCode)
    {
        parent::__construct();
        $this->gameId = $gameId;
        $this->playerCode = $playerCode;

        $game = (new GameModel())->getGameById($gameId);

        $playerModel = new PlayerModel();
        $player = $playerModel->getPlayerByCode($playerCode);

        $gameField = (new GameFieldModel())->getByGameAndPlayer($game->getId(), $player->getId());

        $shipPlacementModel = new ShipPlacementModel();
        $shipPlacementModel->fillFieldAndUsedPlaces($gameField->getId());
        $this->field = $shipPlacementModel->getField();
    }

    public function setShipToField(int $shipX, int $shipY, bool $isHorizontal, int $shipSize, string $shipName): void
    {
        if ($isHorizontal) {
            for ($i = 0; $i < $shipSize; $i++) {
                $this->field[$shipX + $i][$shipY] = [$shipName, ShotModel::WAS_NO_SHOT];
            }
        } else {
            for ($i = 0; $i < $shipSize; $i++) {
                $this->field[$shipX][$shipY + $i] = [$shipName, ShotModel::WAS_NO_SHOT];
            }
        }
    }

    /**
     * @throws \Exception
     */
    #[ArrayShape([
        'x' => "array",
        'y' => "array",
        'shipName' => "array",
        'orientation' => "array",
        'fieldAndShip' => "array"
    ])]
    protected function prepareParams(array $params): array
    {
        $shipName = $params['shipName'];
        $shipSize = $params['shipSize'];
        $orientation = $params['orientation'];
        $isHorizontal = $params['isHorizontal'];
        $shipX = $params['shipX'];
        $shipY = $params['shipY'];

        $this->xSizeBound = ShipPlacementModel::FIELD_SIZE - ($isHorizontal ? $shipSize : 0);
        $this->ySizeBound = ShipPlacementModel::FIELD_SIZE - ($isHorizontal ? 0 : $shipSize);

        $preparedParams['x'] = $shipX;
        $preparedParams['y'] = $shipY;
        $preparedParams['shipName'] = $shipName;
        $preparedParams['orientation'] = $orientation;
        $preparedParams['fieldAndShip'] = [
            'field' => $this->field,
            'isHorizontal' => $isHorizontal,
            'shipSize' => $shipSize,
            'shipName' => $shipName,
            'shipX' => $shipX,
            'shipY' => $shipY
        ];

        return $preparedParams;
    }

    #[ArrayShape([
        'x' => "array",
        'y' => "array",
        'shipName' => "array",
        'orientation' => "array",
        'fieldAndShip' => "\Battleship\App\Validator\Rule\IsCorrectShipPlacement[]"
    ])]
    protected function rules(): array
    {
        return [
            'x' => [
                new IfWasErrorsStop(),
                new IsRequired('Координата X'),
                new IsInt('Координата X'),
                new IsInScope(0, $this->xSizeBound)
            ],
            'y' => [
                new IfWasErrorsStop(),
                new IsRequired('Координата Y'),
                new IsInt('Координата Y'),
                new IsInScope(0, $this->ySizeBound)
            ],
            'shipName' => [
                new IfWasErrorsStop(),
                new IsRequired('Название корабля'),
                new IsStringRequired(),
                new IsShipExist()
            ],
            'orientation' => [
                new IfWasErrorsStop(),
                new IsRequired('Ориентация корабля'),
                new IsStringRequired(),
                new IsCorrectOrientation()
            ],
            'fieldAndShip' => [
                new IfWasErrorsStop(),
                new IsNoShipsIntersection()
            ]
        ];
    }
}