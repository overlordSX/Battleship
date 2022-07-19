<?php


require_once "app/database/entity/AbstractEntity.php";
require_once "app/database/entity/ProductEntity.php";
require_once "app/database/entity/CommentEntity.php";

require_once "app/database/util/EntityUtil.php";

require_once "app/database/Products.php";
require_once "app/database/Comments.php";

require_once "app/database/config/config.php";
require_once "app/database/Database.php";
require_once "app/database/QueryBuilder.php";

require_once "view/View.php";

require_once "app/controllers/ControllerInterface.php";
require_once "app/controllers/CatalogController.php";
require_once "app/controllers/ProductController.php";
require_once "app/controllers/CommentController.php";
require_once "app/controllers/AdminController.php";
require_once "app/controllers/ErrorController.php";


require_once "app/validator/Validator.php";
require_once "app/validator/RuleInterface.php";
require_once "app/validator/IsLenAllowed.php";
require_once "app/validator/IsRequired.php";
require_once "app/validator/IsString.php";
require_once "app/validator/IsPosDigit.php";
require_once "app/validator/IsEmail.php";

require_once "app/routes/Router.php";
require_once "app/routes/routes.php";

