<?php

namespace Tualo\Office\Bootstrap\Routes;

use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\Basic\Path;

class PublicRoute implements IRoute
{
    public static function register()
    {
        BasicRoute::add('/bootstrap/(?P<file>[\/.\w\d\-\_\.]+)' . '', function ($matches) {
            if (file_exists(dirname(__DIR__, 2) . '/lib/' . $matches['file'])) {
                App::etagFile(dirname(__DIR__, 2) . '/lib/' . $matches['file'], true);
                BasicRoute::$finished = true;
                http_response_code(200);
            }
        }, ['get'], false);

        BasicRoute::add('/bootstrap/css/(?P<file>[\/.\w\d\-\_\.]+)' . '', function ($matches) {
            if (file_exists(dirname(__DIR__, 2) . '/lib/' . $matches['file'])) {
                App::etagFile(dirname(__DIR__, 2) . '/lib/' . $matches['file'], true);
                BasicRoute::$finished = true;
                http_response_code(200);
            }
        }, ['get'], false);

        BasicRoute::add('/tualocms/page/bootstrap/(?P<file>[\/.\w\d\-\_\.]+)' . '', function ($matches) {
            if (file_exists(dirname(__DIR__, 2) . '/lib/' . $matches['file'])) {
                App::etagFile(dirname(__DIR__, 2) . '/lib/' . $matches['file'], true);
                BasicRoute::$finished = true;
                http_response_code(200);
            }
        }, ['get'], false);

        BasicRoute::add('/tualocms/page/bootstrapbuild/(?P<file>[\/.\w\d\-\_\.]+)' . '', function ($matches) {
            $dir = Path::join(App::get('basePath'), 'scss_build');
            if (file_exists(Path::join($dir, $matches['file']))) {
                App::etagFile(Path::join($dir, $matches['file']), true);
                BasicRoute::$finished = true;
                http_response_code(200);
            } else if (file_exists(Path::join(dirname(__DIR__, 2), 'lib', $matches['file']))) {
                App::etagFile(Path::join(dirname(__DIR__, 2), 'lib', $matches['file']), true);
                BasicRoute::$finished = true;
                http_response_code(200);
            }
        }, ['get'], false);
    }
}
