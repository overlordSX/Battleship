<?
//TODO в метод роут можно добавить параметр, который будет отвечать за тип POST|GET

Router::route(['/db?(.+)'], [DatabaseController::class, 'createTables']);

Router::route(['/api/start/'], [GameController::class, 'startNewGame']);

//TODO из фронта просит запрос без /, а в ТЗ со /
Router::route(['/api/status/(\d+)/(.+)'], [GameController::class, 'getStatus']);

Router::route(['/api/place-ship/(\d+)/(.+)'], [PlacementController::class, 'placeShip']);

Router::route(['/api/clear-field/(\d+)/(.+)/'], [PlacementController::class, 'clearField']);

Router::route(['/api/ready/(\d+)/(.+)/'], [GameController::class, 'setStatus']);

Router::route(['/api/shot/(\d+)/(.+)/'], [ShotController::class, 'makeShot']);

Router::route(['/api/chat-load/(\d+)/(.+)/'], [ChatController::class, 'loadChat']);

Router::route(['/api/chat-send/(\d+)/(.+)/'], [ChatController::class, 'sendMessage']);

