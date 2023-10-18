<?php
namespace Tualo\Office\Bootstrap\Commands;
use Garden\Cli\Cli;
use Garden\Cli\Args;
use phpseclib3\Math\BigInteger\Engines\PHP;
use Tualo\Office\Basic\ICommandline;
use Tualo\Office\ExtJSCompiler\Helper;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\PostCheck;
use Tualo\Office\Bootstrap\ImportSCSS;

class Import implements ICommandline{

    public static function getCommandName():string { return 'import-bootstrap-scss';}

    public static function setup(Cli $cli){
        $cli->command(self::getCommandName())
            ->description('import basic bootstrap scss files')
            ->opt('client', 'only use this client', true, 'string');
            
    }

   
    public static function setupClients(string $msg,string $clientName,string $file,callable $callback){
        $_SERVER['REQUEST_URI']='';
        $_SERVER['REQUEST_METHOD']='none';
        App::run();

        $session = App::get('session');
        $sessiondb = $session->db;
        $dbs = $sessiondb->direct('select username dbuser, password dbpass, id dbname, host dbhost, port dbport from macc_clients ');
        foreach($dbs as $db){
            if (($clientName!='') && ($clientName!=$db['dbname'])){ 
                continue;
            }else{
                App::set('clientDB',$session->newDBByRow($db));
                PostCheck::formatPrint(['blue'],$msg.'('.$db['dbname'].'):  ');
                $callback($file);
                PostCheck::formatPrintLn(['green'],"\t".' done');

            }
        }
    }

    public static function run(Args $args){
        $files = [
            // 'CMS.menu' => 'setup CMS.menu'
        ];

        foreach($files as $file=>$msg){
            $installSQL = function(string $file){

                ImportSCSS::import(false,App::get('clientDB'));
//                $res = App::get('clientDB')->direct('select * from getbootstrap_scss');
                PostCheck::formatPrintLn(['green'],'done');
            };
            $clientName = $args->getOpt('client');
            if( is_null($clientName) ) $clientName = '';
            self::setupClients($msg,$clientName,$file,$installSQL);
        }



    }
}
