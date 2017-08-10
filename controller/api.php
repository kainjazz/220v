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
    protected $requestParams = null;
    protected $secondUrlParam = null;
    protected $firstUrlParam = null;

    public function __construct($params=null)
    {
        require (__DIR__ . '/../model/db.php');
        $this->db = model\DB::getInstance();
        if(!is_null($params)) {
            $verb = $params['verb'];
            $this->firstUrlParam = $params['firstUrlParam'];
            $this->secondUrlParam = $params['secondUrlParam'];
            $this->requestParams = $params['requestParams'];
            method_exists($this,$verb) ? $this->$verb() : $this->index();
        } else {
            $this->index();
        }
    }

    public function index() {
        echo "<br/>Уточните метод";
    }

    public function GET() {
        $vendorArray = [];
        if(!is_null($this->secondUrlParam) && $this->secondUrlParam != '') {
            $try = $this->db->dbQueryArryReturn("SELECT `vendor_id` as vendor, count(*) > $this->secondUrlParam AS cnt FROM `item` GROUP BY `vendor_id`");
            if($try) {
                foreach ($try as $value) {
                    if($value->cnt) {
                        $vendorArray[] = $value->vendor;
                    }
                }
            }
            if(!empty($vendorArray)) {
                $this->status = "ok";
                $this->data = $vendorArray;
                $this->message = "Отобраны брэнды с количеством товаров больше $this->secondUrlParam";
                $this->renderJson();
            } else {
                $this->status = "ok";
                $this->data = $vendorArray;
                $this->message = "Не найдено брэндов с количеством товаров больше $this->secondUrlParam";
                $this->renderJson();
            }
        } else {
            $try = $this->db->dbQueryArryReturn("SELECT * FROM `item` WHERE `vendor_id`='$this->firstUrlParam'");
            if($try) {
                $this->status = "ok";
                $this->data = $try;
                $this->message = "Отобраны товары брэнда $this->firstUrlParam";
                $this->renderJson();
            } else {
                $this->status = "error";
                $this->data = $try;
                $this->message = "Ошибка запроса";
                $this->renderJson();
            }
        }

    }

    /**
     * В задаче не было задания реализовать изменение товара или брэнда ***************************************************
     * @deprecated
     */
    public function POST() {}

    public function PUT() {
        /**
         * Добавление товара
         */
        if($this->firstUrlParam == 'item') {
            if($this->requestParams['vendor'] != '') {
                $vendor = $this->requestParams['vendor'];
                $try = $this->db->dbQueryArryReturn("SELECT * FROM `vendor` WHERE `vendor_id`='$vendor'");
                if(!empty($try)) {
                    if($this->paramsValidate($this->requestParams)) {
                        $title = $this->requestParams['title'];
                        $vendor = $this->requestParams['vendor'];
                        $price = $this->requestParams['price'];
                        $short = $this->requestParams['short'];
                        $try = $this->db->simpleQuery("INSERT INTO `item`(`title`, `vendor_id`, `price_retail`, `short_description`) VALUES ('$title','$vendor','$price','$short')");
                        if($try) {
                            $this->status = "ok";
                            $this->data = [];
                            $this->message = "Товар успешно добавлен";
                            $this->renderJson();
                        } else {
                            $this->status = "error";
                            $this->data = [];
                            $this->message = "Товар не добавлен, произошла ошибка";
                            $this->renderJson();
                        }
                    } else {
                        $this->status = "error";
                        $this->data = [];
                        $this->message = "Данные введены не верно, проверьте все поля";
                        $this->renderJson();
                    }
                } else {
                    $this->status = "error";
                    $this->data = [];
                    $this->message = "Брэнд не найден";
                    $this->renderJson();
                }
            } else {
                $this->status = "error";
                $this->data = [];
                $this->message = "Поле Брэнд не может быть пустым";
                $this->renderJson();
            }
        }

        /**
         * Добавление брэнда
         */
        if($this->firstUrlParam == 'brand') {
            if($this->paramsValidate($this->requestParams, true)) {
                $title = $this->requestParams['title'];
                $vendor = $this->requestParams['vendor'];
                $short = $this->requestParams['short'];
                $try = $this->db->simpleQuery("INSERT INTO `vendor`(`vendor_id`, `title`, `short_description`) VALUES ('$vendor','$title','$short')");
                if($try) {
                    $this->status = "ok";
                    $this->data = [];
                    $this->message = "Брэнд успешно добавлен";
                    $this->renderJson();
                } else {
                    $this->status = "error";
                    $this->data = [];
                    $this->message = "Брэнд не добавлен, произошла ошибка";
                    $this->renderJson();
                }
            } else {
                $this->status = "error";
                $this->data = [];
                $this->message = "Заполнены не все поля";
                $this->renderJson();
            }
        }
    }

    public function DELETE() {
        if($this->firstUrlParam == 'item') {
            /**
             * @todo Нет ошибки, даже если второй параметр типа 14345fdsg, наш код берёт цифры и игнорирует буквы. Тут надо подумать.
             */
            if(is_numeric($this->secondUrlParam)) {
                $id = $this->secondUrlParam;
                $isItem = $this->db->dbQueryArryReturn("SELECT * FROM `item` WHERE `id`='$id'");
                $this->db->simpleQuery("DELETE FROM `item` WHERE `id`='$id'");
                $checkDeleted = $this->db->dbQueryArryReturn("SELECT * FROM `item` WHERE `id`='$id'");
                if(empty($checkDeleted) && !empty($isItem)) {
                    $this->status = "ok";
                    $this->data = [];
                    $this->message = "Товар успешно удалён";
                    $this->renderJson();
                } else {
                    $this->status = "error";
                    $this->data = [];
                    $this->message = "Товар не удалён, произошла ошибка";
                    $this->renderJson();
                }
            } else {
                $this->status = "error";
                $this->data = [];
                $this->message = "Неверно указан id товара";
                $this->renderJson();
            }
        }
        elseif($this->firstUrlParam == 'brand') {

            $vendor = $this->secondUrlParam;
            $try = $this->db->dbQueryArryReturn("SELECT * FROM `vendor` WHERE `vendor_id`='$vendor'");

            if(!empty($try)) {
                $id = $this->secondUrlParam;
                $isVendor = $this->db->dbQueryArryReturn("SELECT * FROM `vendor` WHERE `vendor_id`='$id'");
                $this->db->simpleQuery("DELETE FROM `vendor` WHERE `vendor_id`='$id'");
                $checkDeleted = $this->db->dbQueryArryReturn("SELECT * FROM `vendor` WHERE `vendor_id`='$id'");
                if(empty($checkDeleted) && !empty($isVendor)) {
                    $this->status = "ok";
                    $this->data = [];
                    $this->message = "Брэнд успешно удалён";
                    $this->renderJson();
                } else {
                    $this->status = "error";
                    $this->data = [];
                    $this->message = "Брэнд не удалён, произошла ошибка";
                    $this->renderJson();
                }
            } else {
                $this->status = "error";
                $this->data = [];
                $this->message = "Брэнд не найден";
                $this->renderJson();
            }



        }
    }

    public function paramsValidate($paramsArray, $isBrand = false) {
        if(!$isBrand) {
            foreach ($paramsArray as $key => $value) {
                if ($value == "") {
                    return false;
                } else {
                    if ($key == 'price' && !is_numeric($value)) {
                        return false;
                    }
                }
            }
        } else {
            foreach ($paramsArray as $key => $value) {
                if ($value == "" && $key != 'price') {
                    return false;
                }
            }
        }
        return true;
    }
}