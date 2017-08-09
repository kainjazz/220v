<?php
/**
 * Created by PhpStorm.
 * User: Koshpaev SV
 * Date: 04.08.2017
 * Time: 14:20
 */

require "jsonAnswer.php";

class root
{
    public $status;
    public $data;
    public $message;

    public function renderJson() {
        $answer = new jsonAnswer();
        $answer->status = $this->status;
        $answer->data= $this->data;
        $answer->message = $this->message;

        $go = json_encode($answer);
        echo $go;
    }
}