<?php
/**
 * Created by PhpStorm.
 * User: Koshpaev SV
 * Date: 04.07.2017
 * Time: 21:01
 */

require "REST.php";
$actualRoute = $_SERVER["REQUEST_URI"];

$pattern = "/api/";
$matches = [];
$api = preg_match( "/\/api\/[\w]+/",$actualRoute);
$api ? preg_match_all("/\/api\/([\w]+)[\/]?/",$actualRoute,$matches) : null;
$getArray = $_GET;

if($api) {
    $REST = new REST();
    echo $REST->verb;
    var_dump($REST->getFormData($REST->verb));
    echo "<br/>";
}


$allRouts = [
    '/' => 'view/index.php',
    'error' => 'view/error.php',
    '/howitworks' => 'view/howitworks.php', //В обход контроллера  - только для теста-демонстрации
    'api' => [
        'controller' => 'api',
        'params' =>[
            'method' => isset($matches[1][0]) ? $matches[1][0] : null,
            'getParams' => $getArray
        ]
    ]
];