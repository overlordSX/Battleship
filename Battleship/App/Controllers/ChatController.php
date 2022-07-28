<?php

namespace Battleship\App\Controllers;

use Battleship\App\Controllers\Util\JsonUtil;
use Battleship\App\Database\Model\MessageModel;

class ChatController implements ControllerInterface
{

    /**
     * @throws \Exception
     */
    public function loadChat($gameId, $playerCode)
    {
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