<?php

namespace Battleship\App\Validator\Rule;

use Battleship\App\Database\Model\GameModel;
use Battleship\App\Validator\RuleInterface;

class IsGameExist implements RuleInterface
{

    /** @throws \Exception */
    public function pass($value): bool
    {
        $gameModel = new GameModel();
        return (bool)$gameModel->query()
            ->where('id', '=', $value)
            ->fetchCount();
    }

    public function message(): string
    {
        return "Игры с таким Id не существует";
    }
}