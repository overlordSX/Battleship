<?php

namespace Battleship\App\Database\Model;

use Battleship\App\Database\Entity\ShotEntity;
use JetBrains\PhpStorm\ArrayShape;

class ShotModel extends AbstractModel
{

    protected string $tableName = 'shot';
    protected string $entityClassName = ShotEntity::class;

    /**
     * @param $gameFieldId
     * @return ShotEntity[]
     * @throws \Exception
     */
    public function getShotsArray($gameFieldId): array
    {
        $shots = $this
            ->query()
            ->where('game_field_id', '=', $gameFieldId)
            ->select('coordinate_x as x', 'coordinate_y as y')
            ->fetchAllToArray();

        $res = [];
        foreach ($shots as $shot) {
            $x = $shot['x'];
            $y = $shot['y'];
            $res[$x][$y] = 1;
        }

        //var_dump($res);

        return $res;
    }

    /**
     * @throws \Exception
     */
    #[ArrayShape(['success' => "bool"])]
    public function makeShot($gameId, $playerCode): array
    {

        $coordinateX ??= $_POST['x'];
        $coordinateY ??= $_POST['y'];

        $playerModel = new PlayerModel();
        $currentPlayer = $playerModel->getPlayerByCode($playerCode);

        $gameFieldModel = new GameFieldModel();
        $currentGameField = $gameFieldModel->getByGameAndPlayer($gameId, $currentPlayer->getId());


        return ['success' => $this->insert([
                'coordinate_x' => $coordinateX,
                'coordinate_y' => $coordinateY,
                'game_field_id' => $currentGameField->getId(),
            ])];
    }


}