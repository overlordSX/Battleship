<?php

namespace Battleship\App\Database\Model;


use Battleship\App\Database\Entity\MessageEntity;
use Exception;

/**
 * Аттрибуты:
 * created_at,
 * content,
 * game_id,
 * player_id
 */
class MessageModel extends AbstractModel
{
    public const MESSAGE_MAX_LEN = 250;
    protected const CHAT_MAX_MESSAGE_QUANTITY = 500;
    protected const ZERO_LAST_TIME = 0;

    protected string $tableName = 'message';
    protected string $entityClassName = MessageEntity::class;

    /**
     * @param $gameId
     * @param $playerCode
     * @return bool
     * @throws Exception
     */
    public function postNewMessage($gameId, $playerCode): bool
    {
        $content = htmlspecialchars(substr($_POST['message'], 0, self::MESSAGE_MAX_LEN), ENT_QUOTES);

        $playerModel = new PlayerModel();
        $player = $playerModel->getPlayerByCode($playerCode);

        return $this->insert([
            'created_at' => time(),
            'content' => $content,
            'game_id' => $gameId,
            'player_id' => $player->getId()
        ]);
    }

    /** @throws Exception */
    public function getChatMessages($gameId, $playerCode): array
    {
        $playerModel = new PlayerModel();
        $currentPlayer = $playerModel->getPlayerByCode($playerCode);

        $isLastTimeExistAndNotFalse = isset($_GET['lastTime']) && (bool)$_GET['lastTime'] !== false;

        $lastTime = $isLastTimeExistAndNotFalse ? (int)$_GET['lastTime'] : self::ZERO_LAST_TIME;

        $allMes = $this->getAllMessages($gameId, $lastTime);

        if (!$allMes) {
            $result['lastTime'] = $lastTime;
        } else {
            $lastMes = $allMes[count($allMes) - 1];
            $result['lastTime'] = $lastMes->getCreatedAt();
        }

        $result['success'] = true;

        foreach ($allMes as $mes) {
            $result['messages'][] = [
                'my' => $mes->getPlayerId() === $currentPlayer->getId(),
                'time' => $mes->getCreatedAt(),
                'message' => $mes->getContent()
            ];
        }

        return $result;
    }

    /**
     * @param int $gameId
     * @param int $lastTime
     * @return MessageEntity[]
     * @throws Exception
     */
    public function getAllMessages(int $gameId, int $lastTime): array
    {
        return $this->query()
            ->where('game_id', '=', $gameId)
            ->where('created_at', '>', $lastTime)
            ->select('player_id', 'created_at', 'content')
            ->orderBy('created_at', 'asc')
            ->limit(self::CHAT_MAX_MESSAGE_QUANTITY)
            ->fetchAll();
    }
}