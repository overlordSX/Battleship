<?php

namespace Battleship\App\Database\Model;

use Battleship\App\Database\Entity\AbstractEntity;
use Battleship\App\Database\Entity\GameEntity;
use Battleship\App\Database\Entity\GameFieldEntity;
use Battleship\App\Database\Entity\PlayerEntity;
use Battleship\App\Database\Entity\ShipEntity;
use Battleship\App\Database\Entity\ShipPlacementEntity;
use Battleship\App\Helpers\ShipOrientationHelper;
use Exception;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Аттрибуты:
 * coordinate_x,
 * coordinate_y,
 * orientation,
 * ship_id,
 * game_field_id
 */
class ShipPlacementModel extends AbstractModel
{

    public const EMPTY_CELL_NAME = 'empty';
    public const FIELD_EDGE_COORDINATE = 9;
    public const FIELD_SIZE = 10;
    public const SHIPS_ON_FIELD = 10;
    public const HORIZONTAL_SHIP_ORIENTATION = 'horizontal';
    public const VERTICAL_SHIP_ORIENTATION = 'vertical';

    protected string $tableName = 'ship_placement';
    protected string $entityClassName = ShipPlacementEntity::class;

    protected GameEntity $game;
    protected PlayerEntity $player;
    protected array $usedPlaces = [];
    protected array $placedShips = [];
    protected array $field = [];
    protected int $gameFieldId;

    protected array $firedShots = [];

    /** @throws Exception */
    protected function setGame($gameId): void
    {
        $gameModel = GameModel::getInstance();
        $gameModel->setGame($gameId);
        $this->game = $gameModel->getGame();
    }

    /** @throws Exception */
    protected function setPlayer($playerCode): void
    {
        $playerModel = new PlayerModel();
        $this->player = $playerModel->getPlayerByCode($playerCode);
    }

    /**
     * @return GameEntity
     */
    public function getGame(): GameEntity
    {
        return $this->game;
    }

    /**
     * @return PlayerEntity
     */
    public function getPlayer(): PlayerEntity
    {
        return $this->player;
    }


    protected function setGameFieldId($gameFieldId): void
    {
        $this->gameFieldId = $gameFieldId;
    }

    /** @throws Exception */
    public function getShips(): array
    {
        if (!$this->placedShips) {
            $this->placedShips = $this->getPlacedShipsList($this->gameFieldId);
        }

        return $this->placedShips;
    }

    public function getUsedPlaces(): array
    {
        return $this->usedPlaces;
    }

    public function getField(): array
    {
        if (!$this->field) {
            $this->field = $this->getEmptyPlacementArray();
        }

        return $this->field;
    }

    /** @throws Exception */
    public function getFiredShots(): array
    {
        if (!$this->firedShots) {
            $shotModel = new ShotModel();
            $this->firedShots = $shotModel->getShotsArray($this->gameFieldId);
        }

        return $this->firedShots;
    }


    public function setUsedPlaces(array $usedPlaces): void
    {
        $this->usedPlaces = $usedPlaces;
    }


    public function setField(array $field): void
    {
        $this->field = $field;
    }

    /** @throws Exception */
    public function makePlacement($gameId, $playerCode): array
    {
        $this->setGame($gameId);
        $this->setPlayer($playerCode);

        $isArray = isset($_POST['ships']);
        $isUnsetShip = isset($_POST['ship'])
            && !isset($_POST['x'])
            && !isset($_POST['y'])
            && !isset($_POST['orientation'])
            && !isset($_POST['ships']);

        $isOneShip = isset($_POST['ship'])
            && isset($_POST['x'])
            && isset($_POST['y'])
            && isset($_POST['orientation'])
            && !isset($_POST['ships']);

        if ($isArray) {
            $shipsQue = $_POST['ships'];
        } elseif ($isUnsetShip) {
            return ['success' => $this->unsetShip($_POST['ship'])];
        } elseif ($isOneShip) {
            $shipsQue = [[
                'x' => $_POST['x'],
                'y' => $_POST['y'],
                'ship' => $_POST['ship'],
                'orientation' => $_POST['orientation']
            ]];
        }

        if (!isset($shipsQue)) {
            return ['success' => false, 'message' => 'вы не передали необходимых параметров'];
        }

        $gameField = (new GameFieldModel())->getByGameAndPlayer($this->game->getId(), $this->player->getId());
        $this->setGameFieldId($gameField->getId());
        $placedShips = $this->getShips();
        $shipsArrayPosition = [];
        if (count($placedShips) > 0) {
            foreach ($placedShips as $number => $placedShip) {
                $shipsArrayPosition[$placedShip->getCustom('name')] = $number;
            }
        }

        foreach ($shipsQue as $ship) {
            if (isset($shipsArrayPosition[$ship['ship']])) {
                $placedShip = $placedShips[$shipsArrayPosition[$ship['ship']]];
            } else {
                $placedShip = [];
            }

            if (!$this->placeShip($gameField, $ship, $placedShip)) {
                return ['success' => false, 'message' => 'произошла ошибка при расстановке'];
            }
        }

        return ['success' => true];
    }


    /** @throws Exception */
    #[ArrayShape(['success' => "bool"])]
    public function clearField($gameId, $playerCode): array
    {
        $playerModel = new PlayerModel();
        $currentPlayer = $playerModel->getPlayerByCode($playerCode);

        $gameFieldModel = new GameFieldModel();
        $currentGameField = $gameFieldModel->getByGameAndPlayer($gameId, $currentPlayer->getId());

        $shipPlacementModel = new ShipPlacementModel();
        return [
            'success' => $shipPlacementModel
                ->delete('game_field_id', '=', $currentGameField->getId())
        ];
    }

    /**
     * @param $gameFieldId
     * @param bool $forEnemy
     * @return void
     * @throws Exception
     */
    public function fillFieldAndUsedPlaces($gameFieldId, bool $forEnemy = false): void
    {
        $this->setGameFieldId($gameFieldId);

        $placedShips = $this->getShips();
        foreach ($placedShips as $placedShip) {
            $this->showOnField($placedShip, $forEnemy);
        }
    }

    /** @throws Exception */
    public function showOnField(ShipPlacementEntity $placedShip, bool $forEnemy = false)
    {
        $field = $this->getField();
        $firedShots = $this->getFiredShots();
        $usedPlaces = $this->getUsedPlaces();

        $x = $placedShip->getCoordinateX();
        $y = $placedShip->getCoordinateY();
        $isHorizontal = $placedShip->getOrientation();
        $placedShipName = $placedShip->getCustom('name');
        $usedPlaces[] = $placedShipName;

        if ($isHorizontal) {
            for ($i = 0; $i < $placedShip->getCustom('size'); $i++) {
                $visibility = $firedShots[$x + $i][$y] ?? 0;

                $field[$x + $i][$y] = [
                    $forEnemy && $visibility || !$forEnemy ? $placedShipName : self::EMPTY_CELL_NAME,
                    $visibility
                ];
            }
        } else {
            for ($i = 0; $i < $placedShip->getCustom('size'); $i++) {
                $visibility = $firedShots[$x][$y + $i] ?? 0;

                $field[$x][$y + $i] = [
                    $forEnemy && $visibility || !$forEnemy ? $placedShipName : self::EMPTY_CELL_NAME,
                    $visibility
                ];
            }
        }

        foreach ($firedShots as $x => $yVal) {
            foreach ($yVal as $y => $value) {
                $field[$x][$y][1] = $value;
            }
        }

        $this->setField($field);
        $this->setUsedPlaces($usedPlaces);
    }


    /**
     * массив 10х10 со значениями ["empty", 0]
     * @return array [[["empty", 0],...]], [[...],... ]],... [[....],..]]]
     */
    protected function getEmptyPlacementArray(): array
    {
        return array_fill(0, self::FIELD_SIZE, array_fill(
            0, self::FIELD_SIZE, [self::EMPTY_CELL_NAME, ShotModel::WAS_NO_SHOT]
        ));
    }


    /**
     * @param $gameFieldId
     * @return ShipPlacementEntity[]
     * @throws Exception
     */
    public function getPlacedShipsList($gameFieldId): array
    {
        return $this
            ->query()
            ->join('ship', 'ship_id', '=', 'id')
            ->where('game_field_id', '=', $gameFieldId)
            ->fetchAll();
    }

    /**
     * @throws Exception
     */
    public function isHereShip(int $x, int $y): bool
    {
        $field = $this->getField();

        return $field[$x][$y][0] !== self::EMPTY_CELL_NAME;
    }

    /**
     * @param string $shipName
     * @return bool
     * @throws Exception
     */
    public function unsetShip(string $shipName): bool
    {
        $shipModel = ShipModel::getInstance();
        $ship = $shipModel->getByName($shipName);
        return $this->delete('ship_id', '=', $ship->getId());
    }

    /**
     * @param GameFieldEntity $gameField
     * @param array $ship
     * @param array|ShipPlacementEntity $placedShip
     * @return bool
     * @throws Exception
     */
    protected function placeShip(GameFieldEntity $gameField, array $ship, ShipPlacementEntity|array $placedShip): bool
    {
        $coordinateX = $ship['x'];
        $coordinateY = $ship['y'];

        $shipName = $ship['ship'];

        $isHorizontal = ShipOrientationHelper::isHorizontalFromString($ship['orientation']);
        $oldOrientation = !$isHorizontal;

        $shipModel = ShipModel::getInstance();
        $currentShip = $shipModel->getByName($shipName);

        if (empty($placedShip)) {
            return $this
                ->insert([
                    'coordinate_x' => $coordinateX,
                    'coordinate_y' => $coordinateY,
                    'orientation' => $isHorizontal,
                    'ship_id' => $currentShip->getId(),
                    'game_field_id' => $gameField->getId()
                ]);
        }

        if ($placedShip->getOrientation() === $oldOrientation) {
            return $this
                ->update(
                    [
                        ['game_field_id' => ['=' => $gameField->getId()]],
                        ['ship_id' => ['=' => $currentShip->getId()]]
                    ],
                    [['orientation' => $isHorizontal]]
                );
        }

        $isCoordinateChanged = $placedShip->getCoordinateX() !== $coordinateX
            || $placedShip->getCoordinateY() !== $coordinateY;

        if ($isCoordinateChanged) {
            return $this
                ->update(
                    [
                        ['game_field_id' => ['=' => $gameField->getId()]],
                        ['ship_id' => ['=' => $currentShip->getId()]]
                    ],
                    [
                        ['coordinate_x' => $coordinateX],
                        ['coordinate_y' => $coordinateY]
                    ]
                );
        }

        return false;
    }

    /**
     * @param int $coordinateX
     * @param int $coordinateY
     * @return ShipEntity
     * @throws Exception
     */
    public function getShip(int $coordinateX, int $coordinateY): AbstractEntity
    {
        $field = $this->getField();
        $shipName = $field[$coordinateX][$coordinateY][0];

        return ShipModel::getInstance()->getByName($shipName);
    }
}
