<?php

class Router
{
    private $routes;

    public function __construct()
    {
        $this->routes = include('/config/routes.php');
    }

    private function getURI(){
      if(!empty($_SERVER['REQUEST_URI'])){
        return trim($_SERVER['REQUEST_URI'],'/');
      }
    }

    public function run()
    {
      //получаем строку запроса
      $uri = $this->getURI();
      //проверяем наличие такого запроса в routes.php
      foreach ($this->routes as $uriPattern => $path) {
        //сравниванем $uriPattern и $uri
        if(preg_match("~$uriPattern~",$uri)){
          //Определяем какой контроллер
          //и action обрабатывают запрос
          $internalRoute = preg_replace("~$uriPattern~",$path,$uri);
          $segment = explode('/',$internalRoute);
          $controllerName = array_shift($segment).'Controller';
          $controllerName = ucfirst($controllerName);

          $actionName = 'action'.ucfirst(array_shift($segment));

          $parameters = $segment;
          //подключаем фаил класса-контроллера
          $controllerFile = '/controllers/'.$controllerName.'.php';
          if(file_exists($controllerFile)){
            include_once($controllerFile);
          }
          //создаем объект и вызываем метод
          $controllerObject = new $controllerName;
          $result = call_user_func_array(array($controllerObject,$actionName),$parameters);
          if($result != null){
            break;
          }

        }
      }
    }

}
