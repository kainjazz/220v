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
$api ? preg_match_all("/\/api\/([\w]+)[\/]?([\d\w]+)?[\/]?/",$actualRoute,$matches) : null;

if($api) {
    $REST = new REST();
    $requestParams = $REST->getFormData($_SERVER['REQUEST_METHOD']);
}

$allRouts = [
    '/' => 'view/index.php',
    'error' => 'view/error.php',
    '/howitworks' => 'view/howitworks.php', //В обход контроллера  - только для теста-демонстрации
    'api' => [
        'controller' => 'api',
        'params' =>[
            'firstUrlParam' => isset($matches[1][0]) ? $matches[1][0] : null,
            'requestParams' => isset($requestParams) ? $requestParams : null,
            'verb' => isset($REST->verb) ? $REST->verb : null,
            'secondUrlParam' => isset($matches[2][0]) ? $matches[2][0] : null
        ]
    ]
];