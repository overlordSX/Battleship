<?php

namespace Battleship\App\Controllers;

class ChatController implements ControllerInterface
{

    public function loadChat()
    {
        echo "hello, it's loadChat";
    }

    public function sendMessage()
    {
        echo "hello, it's sendMessage";
    }
}