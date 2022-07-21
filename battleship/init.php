<?
header('Access-Control-Allow-Origin: *');


require_once 'battleship/app/database/config/config.php';
require_once 'battleship/app/database/Database.php';
require_once 'battleship/app/database/QueryBuilder.php';
require_once 'battleship/app/database/QueryBuilderUtil.php';

require_once 'battleship/app/database/entity/AbstractEntity.php';
require_once 'battleship/app/database/entity/GameEntity.php';
require_once 'battleship/app/database/entity/GameFieldEntity.php';
require_once 'battleship/app/database/entity/GameStatusEntity.php';
require_once 'battleship/app/database/entity/MessageEntity.php';
require_once 'battleship/app/database/entity/PlayerEntity.php';
require_once 'battleship/app/database/entity/ShipEntity.php';
require_once 'battleship/app/database/entity/ShipPlacementEntity.php ';
require_once 'battleship/app/database/entity/ShotEntity.php';

require_once 'battleship/app/database/util/EntityUtil.php';

require_once 'battleship/app/database/models/AbstractModel.php';
require_once 'battleship/app/database/models/PlayerModel.php';
require_once 'battleship/app/database/models/GameStatusModel.php';
require_once 'battleship/app/database/models/GameModel.php';
require_once 'battleship/app/database/models/ShipModel.php';
require_once 'battleship/app/database/models/GameFieldModel.php';
require_once 'battleship/app/database/models/ShipPlacementModel.php';


require_once 'battleship/app/controllers/util/JsonUtil.php';
require_once 'battleship/app/controllers/ControllerInterface.php';
require_once 'battleship/app/controllers/GameController.php';
require_once 'battleship/app/controllers/PlacementController.php';
require_once 'battleship/app/controllers/ShotController.php';
require_once 'battleship/app/controllers/ChatController.php';
require_once 'battleship/app/controllers/DatabaseController.php';


require_once 'battleship/app/routing/Router.php';
require_once 'battleship/app/routing/routes.php';

