<?php

namespace Tualo\Office\Bootstrap\Routes;

use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\Basic\Path;

use Tualo\Office\Basic\RouteSecurityHelper;
use Tualo\Office\SystemFiles\SystemFile;
use Tualo\Office\SystemFiles\SystemFileCallbackResult;

class PublicRoute extends \Tualo\Office\Basic\RouteWrapper
{
    public static function register()
    {
        BasicRoute::add('/bootstrap/(?P<file>[\/.\w\d\-\_\.]+)' . '', function ($matches) {

            if (
                BasicRoute::checkDoubleDots($matches, 'file', 'Path contains ".."') &&
                file_exists(dirname(__DIR__, 2) . '/lib/' . $matches['file'])
            ) {
                App::etagFile(dirname(__DIR__, 2) . '/lib/' . $matches['file'], true);
                BasicRoute::$finished = true;
                http_response_code(200);
            }
        }, ['get'], false);

        BasicRoute::add('/bootstrap/css/(?P<file>[\/.\w\d\-\_\.]+)' . '', function ($matches) {
            if (
                BasicRoute::checkDoubleDots($matches, 'file', 'Path contains ".."') &&
                file_exists(dirname(__DIR__, 2) . '/lib/' . $matches['file'])
            ) {
                App::etagFile(dirname(__DIR__, 2) . '/lib/' . $matches['file'], true);
                BasicRoute::$finished = true;
                http_response_code(200);
            }
        }, ['get'], false);

        BasicRoute::add('/tualocms/page/bootstrap/(?P<file>[\/.\w\d\-\_\.]+)' . '', function ($matches) {
            if (
                BasicRoute::checkDoubleDots($matches, 'file', 'Path contains ".."') &&
                file_exists(dirname(__DIR__, 2) . '/lib/' . $matches['file'])
            ) {
                App::etagFile(dirname(__DIR__, 2) . '/lib/' . $matches['file'], true);
                BasicRoute::$finished = true;
                http_response_code(200);
            }
        }, ['get'], false);



        BasicRoute::add('/tualocms/page/bootstrapbuild/(?P<file>[\/.\w\d\-\_\.]+)' . '', function ($matches) {
            $dir = Path::join(App::get('basePath'), 'scss_build');
            $libDir = Path::join(dirname(__DIR__, 2), 'lib');
            $requestedFile = $matches['file'];

            // 1. Path Traversal Schutz
            if (!RouteSecurityHelper::isSecurePath($requestedFile)) {
                http_response_code(404);
                return false;
            }

            // 2. Sichere Pfad-Auflösung
            $safePath = RouteSecurityHelper::resolveSafePath($requestedFile, $dir) || RouteSecurityHelper::resolveSafePath($requestedFile, $libDir);
            if (!$safePath) {
                http_response_code(404);
                return false;
            }

            /*
            if (
                BasicRoute::checkDoubleDots($matches, 'file', 'Path contains ".."') &&
                   file_exists(Path::join($dir, $matches['file'])) 
            ) {
                App::etagFile(Path::join($dir, $matches['file']), true);
                BasicRoute::$finished = true;
                http_response_code(200);
            } else if (file_exists(Path::join(dirname(__DIR__, 2), 'lib', $matches['file']))) {
                App::etagFile(Path::join(dirname(__DIR__, 2), 'lib', $matches['file']), true);
                BasicRoute::$finished = true;
                http_response_code(200);
            } else {
                */

            $allowed = [
                'js' => 'application/javascript',
                'css' => 'text/css',
                'map' => 'application/json',
                'json' => 'application/json',
                'ttf' => 'font/ttf',
                'woff' => 'font/woff',
                'woff2' => 'font/woff2'
            ];
            $requestedFile = $matches['path'];

            SystemFile::deliverFile($requestedFile, function () use ($matches, $dir, $libDir, $allowed) {
                $filePath = Path::join($dir, $matches['file']); // zuerst im Build-Verzeichnis suchen
                if (!file_exists($filePath)) {
                    $filePath = Path::join($libDir, $matches['file']); // wenn nicht gefunden, im lib-Verzeichnis suchen
                    if (!file_exists($filePath)) {
                        return new SystemFileCallbackResult(false);
                    }
                }
                $pathInfo = pathinfo($filePath);
                $extension = strtolower($pathInfo['extension'] ?? '');
                if (!file_exists($filePath)) {
                    return new SystemFileCallbackResult(false);
                }
                return new SystemFileCallbackResult(true, file_get_contents($filePath), $allowed[$extension] ?? 'application/octet-stream');
            });
            // }
        }, ['get'], false);
    }
}
