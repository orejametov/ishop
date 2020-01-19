<?php


namespace ishop;

use RedBeanPHP\R;

require_once ROOT.'/vendor/autoload.php';

class Db
{
    use TSingeltone;

    protected function __construct()
    {
        $db = require_once CONF.'/config_db.php';
        class_alias('\RedBeanPHP\R', '\R');
        R::setup($db['dsn'], $db['user'], $db['pass']);
        if(!R::testConnection()){
            throw new \Exception('Нет соединения с БД', 500);
        }

        R::freeze(true);
        if(DEBUG){
            R::debug(true, 1);
        }

    }
}