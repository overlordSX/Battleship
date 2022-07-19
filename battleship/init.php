<?
header('Access-Control-Allow-Origin: *');


require_once 'battleship/app/database/config/config.php';
require_once 'battleship/app/database/Database.php';
require_once 'battleship/app/database/QueryBuilder.php';

require_once 'battleship/app/controllers/util/JsonUtil.php';
require_once 'battleship/app/controllers/ControllerInterface.php';
require_once 'battleship/app/controllers/GameController.php';
require_once 'battleship/app/controllers/PlacementController.php';
require_once 'battleship/app/controllers/ShotController.php';
require_once 'battleship/app/controllers/ChatController.php';


require_once 'battleship/app/routing/Router.php';
require_once 'battleship/app/routing/routes.php';

