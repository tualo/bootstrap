<?php
namespace Tualo\Office\Bootstrap;

use Tualo\Office\ExtJSCompiler\ICompiler;
use Tualo\Office\ExtJSCompiler\CompilerHelper;

class Compiler implements ICompiler {
    

    public static function getFiles(){
        return CompilerHelper::getFiles(__DIR__,'bootstrap_css',10000);
    }
}