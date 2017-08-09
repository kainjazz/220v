<?php
/**
 * Created by PhpStorm.
 * User: Koshpaev SV
 * Date: 04.07.2017
 * Time: 21:01
 */

$actualRoute = $_SERVER["REQUEST_URI"];

$pattern = "/api/";
$matches = [];
$api = preg_match( "/\/api\/[\w]+/",$actualRoute);
$api ? preg_match_all("/\/api\/([\w]+)([\/\?])/",$actualRoute,$matches) : null;

$allRouts = [
    '/' => 'view/index.php',
    'error' => 'view/error.php',
    'api' => [
        'controller' => 'api',
        'params' =>[
            'method' => isset($matches[1][0]) ? $matches[1][0] : null,
        ]
    ]
];