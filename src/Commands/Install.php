<?php
namespace Tualo\Office\Bootstrap\Commands;
use Garden\Cli\Cli;
use Garden\Cli\Args;
use phpseclib3\Math\BigInteger\Engines\PHP;
use Tualo\Office\Basic\ICommandline;
use Tualo\Office\Basic\CommandLineInstallSQL;
use Tualo\Office\ExtJSCompiler\Helper;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\PostCheck;

class Install extends CommandLineInstallSQL  implements ICommandline{
    public static function getDir():string {   return dirname(__DIR__,1); }
    public static $shortName  = 'bootstrap';
    public static $files = [
        'install/ddl' => 'setup ddl',
        'install/addcommand' => 'setup addcommand',
        'install/getbootstrap_scss' => 'setup getbootstrap_scss',
        'install/getbootstrap_scss.ds' => 'setup getbootstrap_scss.ds'
    ];

}
