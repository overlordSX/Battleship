<?

use Battleship\App\Routing\Router;

use Battleship\App\Controllers as Con;

Router::route('GET', ['/db?(.+)'], [Con\DatabaseController::class, 'createTables']);

Router::route('POST', ['/api/start/'], [Con\GameController::class, 'startNewGame']);

//TODO из фронта просит запрос без /, а в ТЗ со /
Router::route('POST', ['/api/status/(\d+)/(.+)'], [Con\GameController::class, 'getStatus']);

Router::route('POST', ['/api/place-ship/(\d+)/(.+)'], [Con\PlacementController::class, 'placeShip']);

Router::route('POST', ['/api/clear-field/(\d+)/(.+)'], [Con\PlacementController::class, 'clearField']);

Router::route('POST', ['/api/ready/(\d+)/(.+)'], [Con\GameController::class, 'setReady']);

Router::route('POST', ['/api/shot/(\d+)/(.+)/'], [Con\ShotController::class, 'makeShot']);

Router::route('GET', ['/api/chat-load/(\d+)/(.+)/'], [Con\ChatController::class, 'loadChat']);

Router::route('POST', ['/api/chat-send/(\d+)/(.+)/'], [Con\ChatController::class, 'sendMessage']);

