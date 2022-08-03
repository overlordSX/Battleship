<?php

namespace Battleship\App\Controllers;

use Battleship\App\Controllers\Util\JsonUtil;
use Battleship\App\Database\Model\MessageModel;
use Battleship\App\Validator\Request\LoadChatRequest;
use Battleship\App\Validator\Request\SendMessageRequest;

class ChatController implements ControllerInterface
{

    /** @throws \Exception */
    public function loadChat(int $gameId, string $playerCode)
    {
        $loadChatRequest = new LoadChatRequest();
        $loadChatRequest->validate(['gameId' => $gameId, 'playerCode' => $playerCode]);
        $requestAnswer = $loadChatRequest->answer();

        if ($requestAnswer) {
            JsonUtil::makeAnswer($requestAnswer);
        }

        $messageModel = new MessageModel();
        $chatMessages = $messageModel->getChatMessages($gameId, $playerCode);

        JsonUtil::makeAnswer($chatMessages);
    }

    /**  @throws \Exception */
    public function sendMessage(int $gameId, string $playerCode): void
    {
        $sendMessageRequest = new SendMessageRequest();
        $sendMessageRequest->validate(['gameId' => $gameId, 'playerCode' => $playerCode]);

        $messageModel = new MessageModel();
        $success['success'] = $messageModel->postNewMessage($gameId, $playerCode);

        JsonUtil::makeAnswer($success);
    }
}