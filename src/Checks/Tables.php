<?php
namespace Tualo\Office\Bootstrap\Checks;

use Tualo\Office\Basic\Middleware\Session;
use Tualo\Office\Basic\PostCheck;
use Tualo\Office\Basic\TualoApplication as App;


class Tables  extends PostCheck {
    
    public static function test(array $config){
        $clientdb = App::get('clientDB');
        if (is_null($clientdb)) return;
        $tables = [
            'getbootstrap_scss'=>[]
        ];
        self::tableCheck('bootstrap',$tables,
            "please run the following command: `./tm install-sql-bootstrap --client ".$clientdb->dbname."`",
            "please run the following command: `./tm install-sql-bootstrap --client ".$clientdb->dbname."`"

        );
    }
}