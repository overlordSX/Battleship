<?

Router::route(['/api/start/'], GameController::class, 'startGame');

Router::route(['/api/status/(\d+)/(.+)/'], GameController::class, 'getStatus');

Router::route(['/api/place-ship/(\d+)/(.+)/'], PlacementController::class, 'placeShip');

Router::route(['/api/clear-field/(\d+)/(.+)/'], PlacementController::class, 'clearField');

Router::route(['/api/ready/(\d+)/(.+)/'], GameController::class, 'setStatus');

Router::route(['/api/shot/(\d+)/(.+)/'], ShotController::class, 'makeShot');

Router::route(['/api/chat-load/(\d+)/(.+)/'], ChatController::class, 'loadChat');

Router::route(['/api/chat-send/(\d+)/(.+)/'], ChatController::class, 'sendMessage');