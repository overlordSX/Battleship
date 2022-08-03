<?
require_once 'Battleship/App/Autoloader.php';

use Battleship\App\Autoloader;

require_once 'Battleship/App/Database/config/config.php';
Autoloader::register('Battleship/App/Database/Database');
Autoloader::register('Battleship/App/Database/QueryBuilder');
Autoloader::register('Battleship/App/Database/Entity/AbstractEntity');
Autoloader::register('Battleship/App/Database/Entity/GameEntity');
Autoloader::register('Battleship/App/Database/Entity/GameFieldEntity');
Autoloader::register('Battleship/App/Database/Entity/GameStatusEntity');
Autoloader::register('Battleship/App/Database/Entity/MessageEntity');
Autoloader::register('Battleship/App/Database/Entity/PlayerEntity');
Autoloader::register('Battleship/App/Database/Entity/ShipEntity');
Autoloader::register('Battleship/App/Database/Entity/ShipPlacementEntity');
Autoloader::register('Battleship/App/Database/Entity/ShotEntity');

Autoloader::register('Battleship/App/Database/Util/EntityUtil');

Autoloader::register('Battleship/App/Database/Models/AbstractModel');
Autoloader::register('Battleship/App/Database/Models/PlayerModel');
Autoloader::register('Battleship/App/Database/Models/GameStatusModel');
Autoloader::register('Battleship/App/Database/Models/GameModel');
Autoloader::register('Battleship/App/Database/Models/ShipModel');
Autoloader::register('Battleship/App/Database/Models/GameFieldModel');
Autoloader::register('Battleship/App/Database/Models/ShipPlacementModel');
Autoloader::register('Battleship/App/Database/Models/ShotModel');
Autoloader::register('Battleship/App/Database/Models/MessageModel');

Autoloader::register('Battleship/App/Validator/RuleInterface');
Autoloader::register('Battleship/App/Validator/Rules/IsGameExist');
Autoloader::register('Battleship/App/Validator/Rules/IsPlayerExist');
Autoloader::register('Battleship/App/Validator/Rules/IsPosInt');
Autoloader::register('Battleship/App/Validator/Rules/IsGameWithPlayerExist');
Autoloader::register('Battleship/App/Validator/Rules/IsString');
Autoloader::register('Battleship/App/Validator/Rules/IsCorrectLastTime');
Autoloader::register('Battleship/App/Validator/Rules/IsMaxLenCorrect');
Autoloader::register('Battleship/App/Validator/Rules/IsRequired');
Autoloader::register('Battleship/App/Validator/Rules/IsCorrectGameStatus');
Autoloader::register('Battleship/App/Validator/Rules/IsLessEdge');
Autoloader::register('Battleship/App/Validator/Rules/IsPlayerNotReady');
Autoloader::register('Battleship/App/Validator/Rules/IsShotNotExist');
Autoloader::register('Battleship/App/Validator/Rules/IsThisPlayerTurn');
Autoloader::register('Battleship/App/Validator/Rules/IsInt');
Autoloader::register('Battleship/App/Validator/Rules/IsInScope');
Autoloader::register('Battleship/App/Validator/Rules/IfWasErrorsStop');
Autoloader::register('Battleship/App/Validator/Rules/IsCorrectShipName');
Autoloader::register('Battleship/App/Validator/Rules/IsShipOnField');
Autoloader::register('Battleship/App/Validator/Rules/IsShipExist');
Autoloader::register('Battleship/App/Validator/Validator');

Autoloader::register('Battleship/App/Validator/Request/AbstractRequest');
Autoloader::register('Battleship/App/Validator/Request/BaseRequest');
Autoloader::register('Battleship/App/Validator/Request/PlayerReadyRequest');
Autoloader::register('Battleship/App/Validator/Request/LoadChatRequest');
Autoloader::register('Battleship/App/Validator/Request/SendMessageRequest');
Autoloader::register('Battleship/App/Validator/Request/MakeShotRequest');
Autoloader::register('Battleship/App/Validator/Request/UnsetShipRequest');

Autoloader::register('Battleship/App/Controllers/Util/JsonUtil');
Autoloader::register('Battleship/App/Controllers/ControllerInterface');
Autoloader::register('Battleship/App/Controllers/GameController');
Autoloader::register('Battleship/App/Controllers/PlacementController');
Autoloader::register('Battleship/App/Controllers/ShotController');
Autoloader::register('Battleship/App/Controllers/ChatController');
Autoloader::register('Battleship/App/Controllers/DatabaseController');


Autoloader::register('Battleship/App/Routing/Router');
require_once 'Battleship/App/Routing/routes.php';

