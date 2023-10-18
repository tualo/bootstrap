<?php
namespace Tualo\Office\Bootstrap\Checks;

use Tualo\Office\Basic\Middleware\Session;
use Tualo\Office\Basic\PostCheck;
use Tualo\Office\Basic\TualoApplication as App;


class CheckEmpty  extends PostCheck {
    
    public static function test(array $config){
        $clientdb = App::get('clientDB');
        if (is_null($clientdb)) return;
        
        $res = App::get('clientDB')->direct('select * from getbootstrap_scss');
        if (count($res)==0){
            PostCheck::formatPrintLn(['red'],'bootstrap_scss is empty');
            PostCheck::formatPrintLn(['blue'],'please run the following command: `./tm import-bootstrap-scss --client '.$clientdb->dbname.'`');
        }else{
            PostCheck::formatPrintLn(['green'],'bootstrap_scss is not empty');
        }
    }
}