<?php


namespace ishop;


class Router
{
    protected static $routes = [];
    protected static $route = [];

    public static function add($regexp, $route = []){
        self::$routes[$regexp] = $route;
    }

    public static function getRoutes(){
        return self::$routes;
    }

    public static function getRoute(){
        return self::$route;
    }

    public static function dispatch($url){
        $url = self::removeQueryString($url);
        if(self::matchRoute($url)){
           $controller = 'app\controllers\\'.self::$route['prefix'].self::$route['controller'] . 'Controller'; #postFix
           if(class_exists($controller)){
               $controllerObject = new $controller(self::$route);
               $action = self::lowerCamelCase($action = self::$route['action']) . 'Action'; #postFix
               if(method_exists($controllerObject, $action)){
                    $controllerObject->$action();
                    $controllerObject->getView();
               }else {
                   throw new \Exception("Метод $controller::$action не найден", 404);
               }
           }else {
               throw new \Exception("Контроллер не найден",     404);

           }
        }else {
            throw new \Exception('Страница не найдена', 404);
        }
    }

    public static function matchRoute($url){
        foreach (self::$routes as $pattern => $route){
            if(preg_match("#{$pattern}#", $url, $matches)){
                foreach ($matches as $k => $v){
                    if(is_string($k)){
                        $route[$k] = $v;
                    }
                }
                if(empty($route['action'])){
                    $route['action'] = 'index';
                }
                if(!isset($route['prefix'])){
                    $route['prefix'] = '';
                }else {
                    $route['prefix'] .= '\\';
                }
                $route['controller'] = self::upperCamleCase($route['controller']);
                self::$route = $route;
//                debug(self::$route);
                return true;
            }
        }
        return false;
    }
    //CamelCase
    protected static function upperCamleCase($name){
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));
    }
    //camelCase
    protected static function lowerCamelCase($name){
        return lcfirst(self::upperCamleCase($name));
    }

    protected static function removeQueryString($url){
        if($url){
            $params = explode('&', $url, 2);
            if(false === strpos($params[0], '=')){ // function strpos() alternative with indexOf
                return rtrim($params[0], '/');
            }else{
                return '';
            }
        }
    }
}