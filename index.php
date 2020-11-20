<?php
    session_start();
    //подключение автозагрузчика
    include_once '/app/Autoload.php';

    //вызов routes
    $router = new Router();
    $router->run();

?>
