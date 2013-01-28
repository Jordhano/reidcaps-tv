<?php
    session_start();
    require_once("Config.php");
    require_once("Conection.php");
    $conection = new Conection($config['url'], $config['user'], $config['pass'], $config['database']);
?>
