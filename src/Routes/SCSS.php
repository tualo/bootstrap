<?php
namespace Tualo\Office\Bootstrap\Routes;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\Bootstrap\ImportSCSS;
use MatthiasMullie\Minify\CSS;

class SCSS implements IRoute{
    

    public static function register(){
        
        BasicRoute::add('/bootstrap/import'.'',function($matches){
            App::contenttype('application/json');
            ImportSCSS::import();
            App::result('success', true);
        },['get'],true);

        BasicRoute::add('/bootstrap/compile'.'',function($matches){
            App::contenttype('application/json');

            if(($cmd = App::configuration('scss','cmd',false))==false) throw new \Exception('scss cmd not found');
            $sql = 'select * from getbootstrap_scss';
            $db = App::get('session')->getDB();
            $data = $db->direct($sql);
            foreach($data as $row){
                $filename = App::get('tempPath').'/scss/'.$row['filename'];
                $dirname = dirname($filename);
                if (!is_dir($dirname)){
                    mkdir($dirname,0777,true);
                }
                file_put_contents($filename,$row['content']);
            }
            $resfilename = App::get('basePath').'/scss_build/bootstrap.css';
            if (!is_dir(dirname($resfilename))){
                mkdir(dirname($resfilename),0777,true);
            }
            exec($cmd.' '.App::get('tempPath').'/scss/bootstrap.scss'.' '.$resfilename,$return,$res_code);
            if ($res_code!=0){
                throw new \Exception('scss compile error');
            }else{

            }
            $minifier = new CSS($resfilename);
            $resfilename = App::get('basePath').'/scss_build/bootstrap.min.css';
            $minifier->minify($resfilename);

            App::result('success', true);
        },['get'],true);

    }
    // sass source/stylesheets/index.scss build/stylesheets/index.css
}