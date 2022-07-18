<?php

class ErrorController implements ControllerInterface
{
    public function get404(): void
    {
        View::generateView('view/errors/404.php');
    }

    public function get500(): void
    {
        View::generateView('view/errors/500.php');
    }
}
