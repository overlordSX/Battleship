<?php

namespace Battleship\App\Validator\Rule;

use Battleship\App\Database\Model\GameModel;
use Battleship\App\Database\QueryBuilder;
use Battleship\App\Validator\RuleInterface;

class IsGameWithPlayerExist implements RuleInterface
{

    public function pass($value): bool
    {
        $gameId = $value['gameId'];
        $playerCode = $value['playerCode'];

        $gameModel = new GameModel();
        return (bool)$gameModel->query()
            ->joinFromRow('join player as p1 on (game.first_player_id = p1.id)')
            ->joinFromRow('join player as p2 on (game.second_player_id = p2.id)')
            ->where('game.id', '=', $gameId)
            ->whereBrackets(function ($query, $playerCode) {
                /**
                 * @var $query QueryBuilder
                 */
                $query
                    ->where('p1.code', '=', $playerCode)
                    ->orWhere('p2.code', '=', $playerCode);
            }, $playerCode)
            ->fetchCount();
    }

    public function message(): string
    {
        return 'Данный игрок не участвует в этой игре';
    }
}