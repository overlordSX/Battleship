<?php

namespace Battleship\App\Controllers;


use Battleship\App\Controllers\Util\JsonUtil;
use Battleship\App\Database\Model\GameModel;
use Battleship\App\Validator\Rule\IsGameExist;
use Battleship\App\Validator\Rule\IsGameWithPlayerExist;
use Battleship\App\Validator\Rule\IsPlayerExist;
use Battleship\App\Validator\Rule\IsPosInt;
use Battleship\App\Validator\Rule\IsString;
use Battleship\App\Validator\Validator;
use Exception;

class GameController implements ControllerInterface
{
    /**
     * сюда приходит POST, означающий старт игры
     *
     * нужно отдать обратно JSON:
     *      id игры,
     *      code игрока,
     *      invite код доступа для другого игрока
     *      success статус
     * @throws Exception
     */
    public function startNewGame(): void
    {
        $newGame = new GameModel();
        $data = $newGame->start();

        JsonUtil::makeAnswer($data);
    }

    /**
     * возвращает статус игры
     *
     * @param $gameId
     * @param $playerCode
     * @throws Exception
     */
    public function getStatus($gameId, $playerCode): void
    {
        $statusValidator = new Validator();
        $statusValidator->make(
            [
                'gameId' => (int)$gameId,
                'playerCode' => (string)$playerCode,
                'gameAndPlayer' => ['gameId' => $gameId, 'playerCode' => $playerCode]
            ],
            [
                // gameId & gameAndPlayer: проверять существование не обязательно
                // так как если их не указать роутинг не будет обрабатывать запрос
                'gameId' => [new IsPosInt(), new IsGameExist()],
                'playerCode' => [new IsString(), new IsPlayerExist()],
                'gameAndPlayer' => [new IsGameWithPlayerExist()]
            ]
        );

        $errors = $statusValidator->isValid() ? [] : $statusValidator->getErrors();
        if ($errors) {
            $info['success'] = false;
            $info['error'] = 1;
            $info['message'] = implode("\n", array_values($errors));

            JsonUtil::makeAnswer($info);
        }

        $gameModel = new GameModel();
        $info = $gameModel->getInfo($gameId, $playerCode);

        JsonUtil::makeAnswer($info);
    }

    /**
     * @throws Exception
     */
    public function setReady($gameId, $playerCode): void
    {
        $statusValidator = new Validator();
        $statusValidator->make(
            [
                'gameId' => (int)$gameId,
                'playerCode' => (string)$playerCode,
                'gameAndPlayer' => ['gameId' => $gameId, 'playerCode' => $playerCode]
            ],
            [
                // gameId & gameAndPlayer: проверять существование не обязательно
                // так как если их не указать роутинг не будет обрабатывать запрос
                'gameId' => [new IsPosInt(), new IsGameExist()],
                'playerCode' => [new IsString(), new IsPlayerExist()],
                'gameAndPlayer' => [new IsGameWithPlayerExist()]
            ]
        );

        $gameModel = new GameModel();
        $ready = $gameModel->playersReady($gameId, $playerCode);

        JsonUtil::makeAnswer($ready);
    }


}