<?php

namespace Battleship\App\Validator\Rule;

use Battleship\App\Database\Model\PlayerModel;
use Battleship\App\Validator\RuleInterface;
use Exception;

class IsPlayerExist implements RuleInterface
{

    /** @throws Exception */
    public function pass($value): bool
    {
        $playerModel = new PlayerModel();
        return $playerModel->query()
            ->where('code', '=', $value)
            ->fetchCount();
    }

    public function message(): string
    {
        return 'Игрока с таким кодом не существует';
    }
}