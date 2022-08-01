<?php

namespace Battleship\App\Controllers;

use Battleship\App\Controllers\Util\JsonUtil;
use Battleship\App\Database\Model\MessageModel;
use Battleship\App\Validator\Rule\IsGameExist;
use Battleship\App\Validator\Rule\IsGameWithPlayerExist;
use Battleship\App\Validator\Rule\IsPlayerExist;
use Battleship\App\Validator\Rule\IsPosInt;
use Battleship\App\Validator\Rule\IsString;
use Battleship\App\Validator\Validator;

class ChatController implements ControllerInterface
{

    /**
     * @throws \Exception
     */
    public function loadChat($gameId, $playerCode)
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

        $messageModel = new MessageModel();
        $chatMessages = $messageModel->getChatMessages($gameId, $playerCode);

        JsonUtil::makeAnswer($chatMessages);
    }

    /**
     * @throws \Exception
     */
    public function sendMessage($gameId, $playerCode): void
    {
        //TODO проверить что в сообщение не больше 250 символов, проверить htmlspecialchars
        $messageModel = new MessageModel();
        $success['success'] = $messageModel->postNewMessage($gameId, $playerCode);

        JsonUtil::makeAnswer($success);
    }
}