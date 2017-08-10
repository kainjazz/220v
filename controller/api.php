<?php
/**
 * Created by PhpStorm.
 * User: Koshpaev SV
 * Date: 03.08.2017
 * Time: 22:34
 */

require "root.php";

class api extends root
{
    public $db;
    protected $availibleTables;

    public function __construct($params=null)
    {
        require (__DIR__ . '/../model/db.php');
        $this->db = model\DB::getInstance();
        if(!is_null($params)) {
            method_exists($this,$params['method']) ? $this->$params['method']() : $this->index();
        } else {
            $this->index();
        }
    }

    public function index() {
        echo "<br/>Уточните метод";
    }

    public function table() {
        echo "dfs";
    }
}