<?php
/**
 * Created by PhpStorm.
 * User: koshpaevsv
 * Date: 11.08.17
 * Time: 12:45
 */

namespace model;
require __DIR__ . "/../config/common.php";

class currency
{
    public $request = '';
    public $error = null;
    public $currencyObject = null;
    public $currencyArray = [];

    public function __construct()
    {
        $file = __DIR__ . '/../currencyCache.data';
        $str = file_get_contents($file);
        $obj = json_decode($str);
        preg_match_all('/\d{4}-\d{2}-(\d{1,2})/',$obj->query->created,$matches);
        if($matches[1][0] == date('d')) {
            $this->currencyObject = $obj;
            $this->currencyArray = $this->currencyObjectToArray($this->currencyObject->query->results->rate);
        } else {
            $app = new \app();
            $str = $this->queryFormatter($app->currencyArray);
            $this->request = "https://query.yahooapis.com/v1/public/yql?q=select+*+from+yahoo.finance.xchange+where+pair+=+%22$str%22&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=";
            $try = curl_init();
            curl_setopt($try, CURLOPT_URL, $this->request);
            curl_setopt($try, CURLOPT_HEADER, 0);
            curl_setopt($try, CURLOPT_RETURNTRANSFER, true);
            $answer = curl_exec($try);
            curl_close($try);

            $this->currencyObject = json_decode($answer);
            $this->currencyArray = $this->currencyObjectToArray($this->currencyObject->query->results->rate);

            file_put_contents($file,$answer);
        }


    }

    public function queryFormatter($currencyArray) {
        $str = '';
        $count = 0;
        foreach ($currencyArray as $currency){
            $count++;
            $str .= $currency . 'RUB';
            if($count < count($currencyArray)){
                $str .=',';
            }
        }

        return $str;
    }

    public function currencyObjectToArray ($arr) {
        foreach ($arr as $key) {
            $rezault[$key->id] = $key;
        }
        return $rezault;
    }
}