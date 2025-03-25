<?php

namespace Tualo\Office\Bootstrap\Routes;

use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\Bootstrap\ImportSCSS;
use MatthiasMullie\Minify\CSS;

class SCSS implements IRoute
{


    public static function register()
    {

        BasicRoute::add('/bootstrap/import' . '', function ($matches) {
            App::contenttype('application/json');
            ImportSCSS::import();
            App::result('success', true);
        }, ['get'], true);


        BasicRoute::add('/bootstrap/replaceimport' . '', function ($matches) {
            App::contenttype('application/json');
            ImportSCSS::import(true);
            App::result('success', true);
        }, ['get'], true);


        BasicRoute::add('/bootstrap/compile' . '', function ($matches) {
            App::contenttype('application/json');

            if (($cmd = App::configuration('scss', 'cmd', false)) == false) throw new \Exception('scss cmd not found');
            $sql = 'select * from getbootstrap_scss';
            $db = App::get('session')->getDB();
            $data = $db->direct($sql);
            foreach ($data as $row) {
                $filename = App::get('tempPath') . '/scss/' . $row['filename'];
                $dirname = dirname($filename);
                if (!is_dir($dirname)) {
                    mkdir($dirname, 0777, true);
                }
                file_put_contents($filename, $row['content']);
            }
            $resfilename = App::get('basePath') . '/scss_build/bootstrap.css';
            if (!is_dir(dirname($resfilename))) {
                mkdir(dirname($resfilename), 0777, true);
            }

            $entryPoint = App::get('tempPath') . '/scss/bootstrap.scss';
            if (file_exists(App::get('tempPath') . '/scss/index.scss')) $entryPoint = App::get('tempPath') . '/scss/index.scss';
            exec($cmd . ' ' . $entryPoint . ' ' . $resfilename . ' 2>&1', $return, $res_code);
            App::result('return', $return);
            if ($res_code != 0) {
                App::result('return', $return);
                throw new \Exception('scss compile error');
            } else {
            }
            $minifier = new CSS($resfilename);
            $resfilename = App::get('basePath') . '/scss_build/bootstrap.min.css';
            $minifier->minify($resfilename);

            App::result('success', true);
        }, ['get'], true);
    }
    // sass source/stylesheets/index.scss build/stylesheets/index.css
}
