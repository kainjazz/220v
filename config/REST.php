<?php
/**
 * Created by PhpStorm.
 * User: Koshpaev SV
 * Date: 09.08.2017
 * Time: 20:50
 */

class REST
{
    public $verb;
    public $post;
    public $get;
    public $put;
    public $delete;

    public function __construct()
    {
        $this->verb = $_SERVER['REQUEST_METHOD'];
    }

    function getFormData($method) {

        // GET или POST: данные возвращаем как есть
        if ($method === 'GET') return $_GET;
        if ($method === 'POST') return $_POST;

        // PUT, PATCH или DELETE
        $data = array();
        $exploded = explode('&', file_get_contents('php://input'));

        foreach($exploded as $pair) {
            $item = explode('=', $pair);
            if (count($item) == 2) {
                $data[urldecode($item[0])] = urldecode($item[1]);
            }
        }

        return $data;
    }
}