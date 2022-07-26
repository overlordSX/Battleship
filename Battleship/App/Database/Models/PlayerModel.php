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
     * @return PlayerEntity
     * @throws \Exception
     */
    public function createPlayer(): AbstractEntity
    {
        $code = $this->getNewPlayerCode();

        $this->insert(['code' => $code]);

        return $this->query()
            ->where('code','=', $code)
            ->fetch();
    }

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
            ->fetch();
    }

    protected function getNewPlayerCode(): string
    {
        return uniqid();
    }

    protected function getNewPlayerCodeMd5(): string
    {
        return md5(microtime(true));
    }
}