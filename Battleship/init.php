<?
header('Access-Control-Allow-Origin: *');


require_once 'Battleship/App/Database/Config/config.php';
require_once 'Battleship/App/Database/Database.php';
require_once 'Battleship/App/Database/QueryBuilder.php';
require_once 'Battleship/App/Database/QueryBuilderUtil.php';

require_once 'Battleship/App/Database/Entity/AbstractEntity.php';
require_once 'Battleship/App/Database/Entity/GameEntity.php';
require_once 'Battleship/App/Database/Entity/GameFieldEntity.php';
require_once 'Battleship/App/Database/Entity/GameStatusEntity.php';
require_once 'Battleship/App/Database/Entity/MessageEntity.php';
require_once 'Battleship/App/Database/Entity/PlayerEntity.php';
require_once 'Battleship/App/Database/Entity/ShipEntity.php';
require_once 'Battleship/App/Database/Entity/ShipPlacementEntity.php ';
require_once 'Battleship/App/Database/Entity/ShotEntity.php';

require_once 'Battleship/App/Database/Util/EntityUtil.php';

require_once 'Battleship/App/Database/Models/AbstractModel.php';
require_once 'Battleship/App/Database/Models/PlayerModel.php';
require_once 'Battleship/App/Database/Models/GameStatusModel.php';
require_once 'Battleship/App/Database/Models/GameModel.php';
require_once 'Battleship/App/Database/Models/ShipModel.php';
require_once 'Battleship/App/Database/Models/GameFieldModel.php';
require_once 'Battleship/App/Database/Models/ShipPlacementModel.php';


require_once 'Battleship/App/Controllers/Util/JsonUtil.php';
require_once 'Battleship/App/Controllers/ControllerInterface.php';
require_once 'Battleship/App/Controllers/GameController.php';
require_once 'Battleship/App/Controllers/PlacementController.php';
require_once 'Battleship/App/Controllers/ShotController.php';
require_once 'Battleship/App/Controllers/ChatController.php';
require_once 'Battleship/App/Controllers/DatabaseController.php';


require_once 'Battleship/App/Routing/Router.php';
require_once 'Battleship/App/Routing/routes.php';

