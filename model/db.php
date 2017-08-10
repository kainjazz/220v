<?php
/**
 * Created by PhpStorm.
 * User: Koshpaev SV
 * Date: 06.07.2017
 * Time: 23:21
 */
namespace model;


class DB {
    protected static $_instance;
    public $link;
    public $allContent = [];
    public $allConfig = [];

    private function __construct(){
        require_once (__DIR__ . '/../config/common.php');
        $this->link = mysqli_connect(
            HOST,
            USER,
            PASSWORD,
            DBNAME,
            PORT);
        $this->link->set_charset("utf8");
    }

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    private function __clone(){}
    private function __wakeup(){}

    /**
     * @param $query
     * @return bool| \mysqli | array
     */
    public function dbQueryArryReturn($query) {
        $raw = mysqli_query($this->link,$query);
        if($raw){
            $arr = [];
            while( $row = mysqli_fetch_object($raw) ){
                $arr[] = $row;
            }
            return $arr;
        }
        return false;
    }

    public function simpleQuery($query) {
        $raw = mysqli_query($this->link,$query);
        if($raw){
            return true;
        }
        return false;
    }
}