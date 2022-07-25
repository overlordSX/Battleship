<?php

namespace Battleship\App\Database\Model;

use Battleship\App\Database\Entity\AbstractEntity;
use Battleship\App\Database\Entity\PlayerEntity;

/**
 * Аттрибуты:
 * code
 */
class PlayerModel extends AbstractModel
{
    protected string $tableName = 'player';
    protected string $entityClassName = PlayerEntity::class;


    /**
     * @param $playerCode
     * @return PlayerEntity
     * @throws \Exception
     */
    public function getPlayerByCode($playerCode): AbstractEntity
    {

        return $this
            ->query()
            ->where('code', '=', $playerCode)
            ->select()
            ->fetch();
    }

    /**
     * @param $playerId
     * @return PlayerEntity
     * @throws \Exception
     */
    public function getPlayerById($playerId): AbstractEntity
    {

        return $this
            ->query()
            ->where('id', '=', $playerId)
            ->select()
            ->fetch();
    }

}