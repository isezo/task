<?php
  spl_autoload_register(function ($class_name) {
    $array_paths = array(
        '/models/',
        '/app/',
    );

    foreach ($array_paths as $path){
        $path = $path . $class_name . '.php';
        if(is_file($path)){
            include_once $path;
        }
    }
  });
?>
