<?php


namespace app\controllers;


use ishop\App;
use RedBeanPHP\R;

class CurrencyController extends AppController
{
    public function changeAction(){

        $currency = !empty($_GET['curr']) ? $_GET['curr'] : null;

//          ### First way, get currencies from container
        if($currency) {
            $curr = App::$app->getProperty('currency');
            if(!empty($curr)){
                setcookie('currency', $currency, time() + 3600*24*7, '/');
            }
        }
        redirect();

//          ### Second way, get currencies from DB
//        if($currency){
//            $curr = R::findOne('currency', 'code = ?', [$currency]);
//            if(!empty($curr)){
//                setcookie('currency', $currency, time() + 3600*24*7, '/');
//            }
//        }
//           redirect();

    }
}