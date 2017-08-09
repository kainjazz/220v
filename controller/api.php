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
        $this->availibleTables = [
            'session',
            'users'
        ];
        $this->db = model\DB::getInstance();
        if(!is_null($params)) {
            method_exists($this,$params['method']) ? $this->$params['method']() : $this->index();
        } else {
            $this->index();
        }
    }

    public function index() {
        echo "Уточните метод";
    }

    public function table() {
        if(isset($_GET['table']) && in_array($_GET['table'],$this->availibleTables)) {
            if(isset($_GET['id'])) {
                $q = $this->db->dbQueryArryReturn("select * from `" . $_GET['table'] . "` where `ID`='" . $_GET['id'] . "' ");
            } else {
                $q = $this->db->dbQueryArryReturn("select * from `" . $_GET['table'] . "`");
            }
        } else {
            echo 'Название таблицы - обязательный параметр, убедитесь, что у Вас есть доступ к таблице';
        }
        if($q) {
            $this->status = 'ok';
            $this->data = $q;
            $this->message='';
        } else {
            $this->status = 'error';
            $this->data = [];
            $this->message = 'Произошла ошибка на уровне взаимодействия с базой данных, обратитесь к администратору';
        }

        $this->renderJson();
    }

    /**
     *  Получаем параметры для записи на лекцию, проверяем их и, если всё ок, записываем
     */
    public function SessionSubscribe() {
        if(isset($_GET['sessionId']) && isset($_GET['userId'])) {
            $q = $this->db->dbQueryArryReturn("select * from `users` WHERE `ID`='" . $_GET['userId'] . "'");
            if ($q) {
                $q = $this->db->dbQueryArryReturn("select * from `session` WHERE `ID`='" . $_GET['sessionId'] . "'");
                if($q) {
                    $limit = $q[0]->SpeakersLimit;
                }
            }

            $actualCount = $this->db->dbQueryArryReturn("SELECT * FROM `sessionspeakers` WHERE `SessionId`='" . $_GET['sessionId'] . "'");

            if(!empty($limit)) {
                if(count($actualCount) < $limit) {
                    $q = $this->db->simpleQuery("INSERT INTO `sessionspeakers`(`UserId`, `SessionId`) VALUES ('" . $_GET['userId'] . "','" . $_GET['sessionId'] . "')");
                    if ($q) {
                        $this->status = 'ok';
                        $this->data = [];
                        $this->message = 'Спасибо, вы успешно записаны!';
                    } else {
                        $this->status = 'error';
                        $this->data = [];
                        $this->message = 'Произошла ошибка на уровне взаимодействия с базой данных, обратитесь к администратору. Обратите внимание, что один пользователь не может дважды записаться на одну и ту же лекцию';
                    }
                } else {
                    $this->status = 'ok';
                    $this->data = [];
                    $this->message = 'Извините, все места заняты';
                }
            } else {
                $this->status = 'error';
                $this->data = [];
                $this->message = 'Извините, кажется, Вы ввели некорректные данные';
            }
        } else {
            $this->status = 'error';
            $this->data = [];
            $this->message = 'Извините, кажется, Вы ввели некорректные данные';
        }

        $this->renderJson();
    }
}