<?
Router::route('GET', ['/db?(.+)'], [DatabaseController::class, 'createTables']);

Router::route('POST', ['/api/start/'], [GameController::class, 'startNewGame']);

//TODO из фронта просит запрос без /, а в ТЗ со /
Router::route('POST', ['/api/status/(\d+)/(.+)'], [GameController::class, 'getStatus']);

Router::route('POST', ['/api/place-ship/(\d+)/(.+)'], [PlacementController::class, 'placeShip']);

Router::route('POST', ['/api/clear-field/(\d+)/(.+)/'], [PlacementController::class, 'clearField']);

Router::route('POST', ['/api/ready/(\d+)/(.+)/'], [GameController::class, 'setStatus']);

Router::route('POST', ['/api/shot/(\d+)/(.+)/'], [ShotController::class, 'makeShot']);

Router::route('GET', ['/api/chat-load/(\d+)/(.+)/'], [ChatController::class, 'loadChat']);

Router::route('POST', ['/api/chat-send/(\d+)/(.+)/'], [ChatController::class, 'sendMessage']);

