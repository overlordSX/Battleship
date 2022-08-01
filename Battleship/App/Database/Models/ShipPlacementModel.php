<?php

namespace Battleship\App\Database\Model;

use Battleship\App\Database\Entity\AbstractEntity;
use Battleship\App\Database\Entity\GameEntity;
use Battleship\App\Database\Entity\GameFieldEntity;
use Battleship\App\Database\Entity\PlayerEntity;
use Battleship\App\Database\Entity\ShipEntity;
use Battleship\App\Database\Entity\ShipPlacementEntity;
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

    protected const EMPTY_CELL_NAME = 'empty';
    public const FIELD_SIZE = 9;
    public const SHIPS_ON_FIELD = 10;

    protected string $tableName = 'ship_placement';
    protected string $entityClassName = ShipPlacementEntity::class;


    protected GameEntity $game;
    protected PlayerEntity $player;
    protected array $usedPlaces = [];
    protected array $placedShips = [];
    protected array $field = [];
    protected int $gameFieldId;

    protected array $firedShots = [];

    /**
     * @throws \Exception
     */
    protected function setGame($gameId): void
    {
        $gameModel = new GameModel();
        $this->game = $gameModel->getGameById($gameId);
    }

    /**
     * @throws \Exception
     */
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

    /**
     * @throws \Exception
     */
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

    /**
     * @throws \Exception
     */
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

    /**
     * @throws \Exception
     */
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

        //TODO вот уже и валидация просится, когда переданы все пар
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
                'ship' => substr($_POST['ship'], 0, 3),
                'orientation' => $_POST['orientation']
            ]];
        }

        //TODO к error добавить коды ошибок
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


    /**
     * @throws \Exception
     */
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
     * @return int
     * @throws \Exception
     */
    public function getShipsCount($gameFieldId): int
    {
        return $this
            ->query()
            ->where('game_field_id', '=', $gameFieldId)
            ->fetchCount();
    }

    /**
     * @param $gameFieldId
     * @param bool $forEnemy
     * @return ShipPlacementModel
     * @throws \Exception
     */
    public function fillFieldAndUsedPlaces($gameFieldId, bool $forEnemy = false): static
    {
        $this->setGameFieldId($gameFieldId);

        $placedShips = $this->getShips();
        foreach ($placedShips as $placedShip) {
            $this->showOnField($placedShip, $forEnemy);
        }

        return $this;
    }

    /**
     * @throws \Exception
     */
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
                //TODO без этого у меня появлялась какая то дичь поле
                // теперь данные должны быть валидными и эта проверка не требуется
                if ($x >= 0 && $y >= 0 && $x <= self::FIELD_SIZE && $y <= self::FIELD_SIZE) {
                    $field[$x][$y][1] = $value;
                }
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
        return array_fill(0, 10, array_fill(0, 10, [self::EMPTY_CELL_NAME, 0]));
    }


    /**
     * @param $gameFieldId
     * @return ShipPlacementEntity[]
     * @throws \Exception
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
     * @param $gameFieldId
     * @param $shipId
     * @return ShipPlacementEntity
     * @throws \Exception
     */
    public function getPlacedShip($gameFieldId, $shipId): AbstractEntity
    {
        return $this->query()
            ->where('game_field_id', '=', $gameFieldId)
            ->where('ship_id', '=', $shipId)
            ->fetch();
    }


    /**
     * @throws \Exception
     */
    public function isHereShip(int $x, int $y): bool
    {
        $field = $this->getField();

        return $field[$x][$y][0] !== self::EMPTY_CELL_NAME;
    }

    /**
     * @param string $shipName
     * @return bool
     * @throws \Exception
     */
    public function unsetShip(string $shipName): bool
    {
        $shipName = substr($shipName, 0, 3);
        $shipModel = new ShipModel();
        $ship = $shipModel->getByName($shipName);
        return $this->delete('ship_id', '=', $ship->getId());
    }

    /**
     * @param array $ship
     * @param array|ShipPlacementEntity $placedShip
     * @return bool
     * @throws \Exception
     */
    protected function placeShip(GameFieldEntity $gameField, array $ship, ShipPlacementEntity|array $placedShip): bool
    {

        //TODO что можно будет проверить
        // тип-номер корабля можно будет проверить regex ^[1-4]-[1-4]$
        // $coordinateX ??= (int)$_POST['x']; //int, 0 <= x <= FIELD_SIZE,
        // $coordinateY ??= (int)$_POST['y']; //int, 0 <= y <= FIELD_SIZE,

        $coordinateX = $ship['x'];
        $coordinateY = $ship['y'];

        $shipName = $ship['ship'];

        //это из за того что непонятно откуда берется запятая с фронта
        $shipName = substr($shipName, 0, 3);

        $orientation = ($ship['orientation'] === 'horizontal');
        $oldOrientation = !$orientation;

        $shipModel = new ShipModel();
        $currentShip = $shipModel->getByName($shipName);

        //TODO РАЗВОРОТ КОРАБЛЯ
        // сначала надо проверить стоит ли уже такой корабль:
        // если стоит
        // -> сравнить ориентации
        //      если разные -> поменять ориентацию, но при смене ориентации нужно еще проверять не пересекаются ли корабли
        //      потом после проверки на пересечение с другими кораблями обновить данные в таблице
        //      если одинаковые -> ничего не делать
        // -> сравнить координаты
        //      разные -> поставить новые
        //      одинаковые -> ничего
        // если не стоит -> поставить.

        //TODO это тоже можно заменить на норм вариант

        if (empty($placedShip)) {
            return $this
                ->insert([
                    'coordinate_x' => $coordinateX,
                    'coordinate_y' => $coordinateY,
                    'orientation' => $orientation,
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
                    [['orientation' => $orientation]]
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
     * @throws \Exception
     */
    protected function isAlreadyPlaced($gameFieldId, $shipId): bool
    {
        return $this->query()
            ->where('game_field_id', '=', $gameFieldId)
            ->where('ship_id', '=', $shipId)
            ->fetchCount();

    }

    /**
     * @param int $coordinateX
     * @param int $coordinateY
     * @return ShipEntity
     * @throws \Exception
     */
    public function getShip(int $coordinateX, int $coordinateY): AbstractEntity
    {
        $field = $this->getField();

        $shipName = $field[$coordinateX][$coordinateY][0];

        $shipModel = new ShipModel();
        return $shipModel->getByName($shipName);
    }

    /**
     * @throws \Exception
     */
    public function getStartCell(ShipEntity $ship): array
    {

        return $this->query()
            ->select('coordinate_x as firstX', 'coordinate_y as firstY', 'orientation')
            ->where('game_field_id', '=', $this->gameFieldId)
            ->where('ship_id', '=', $ship->getId())
            ->fetchToArray();
    }
}
