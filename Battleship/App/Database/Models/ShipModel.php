<?php

namespace Battleship\App\Database\Model;

use Battleship\App\Database\Entity\AbstractEntity;
use Battleship\App\Database\Entity\ShipEntity;
use Exception;

/**
 * Аттрибуты:
 * size,
 * quantity
 */
class ShipModel extends AbstractModel
{
    protected static ?ShipModel $_instance = null;

    /** @var ShipEntity[] */
    protected array $allShips;

    /** @throws Exception */
    protected function __construct()
    {
        $this->allShips = $this->getAllShipsQuery();
    }

    public static function getInstance(): ShipModel
    {
        if (null === static::$_instance) {
            static::$_instance = new static();
        }

        return static::$_instance;
    }

    protected string $tableName = 'ship';
    protected string $entityClassName = ShipEntity::class;

    /**
     * @return ShipEntity[]
     * @throws Exception
     */
    protected function getAllShipsQuery(): array
    {
        return $this->query()
            ->fetchAll();
    }

    /**
     * @param $shipName
     * @return AbstractEntity|null
     * @throws Exception
     */
    public function getByName($shipName): ?AbstractEntity
    {
        foreach (self::getInstance()->allShips as $ship) {
            if ($ship->getName() === $shipName) {
                return $ship;
            }
        }
        return null;
    }

    /**
     * @param $shipName
     * @return bool
     * @throws Exception
     */
    public function isShipExist($shipName): bool
    {
        return (bool) $this->getByName($shipName);
    }
}