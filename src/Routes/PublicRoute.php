<?php
namespace Tualo\Office\Bootstrap\Routes;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;


class PublicRoute implements IRoute{
    public static function register(){
        BasicRoute::add('/tualocms/page/bootstrap/(?P<file>[\/.\w\d\-\_\.]+)'.'',function($matches){
            if (file_exists( dirname(__DIR__,2).'/lib/'.$matches['file']) ){
                App::etagFile( dirname(__DIR__,2).'/lib/'.$matches['file'] , true);
                BasicRoute::$finished = true;
                http_response_code(200);
            }
        },['get'],false);
    }
}