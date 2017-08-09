<?php
/**
 * Created by PhpStorm.
 * User: Koshpaev SV
 * Date: 04.07.2017
 * Time: 20:56
 */
require 'config/route.php';

if(isset($allRouts[$actualRoute])) {
    $readyContent = $allRouts[$actualRoute];
} else {
    if($api) {
        $readyContent = $allRouts["api"];
    } else {
        $readyContent = $allRouts['error'];
    }
}

if ($readyContent == $allRouts['error']) {
    require 'view/error.php';
} else {
    if(!is_array($readyContent)) {
        include $readyContent;
    } else {
        require (__DIR__ . '/controller/' . $readyContent['controller'] . '.php');
        $params = isset($readyContent['params']) ? $readyContent['params'] : null;
        $controller = new $readyContent['controller']($params);
    }
}