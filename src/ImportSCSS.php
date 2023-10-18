<?php
namespace Tualo\Office\Bootstrap;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;


class ImportSCSS {

    public static function rglob(string $patterns, $flags = GLOB_NOSORT): array {
        $result = glob($patterns, $flags);
        foreach ($result as $item) {
            if (is_dir($item)) {
                array_push($result, ...self::rglob($item . '/*', $flags));
            }
        }
    
        return $result;
    }

    public static function import(bool $replace=false, mixed $db=null){
        if (is_null($db)) $db = App::get('session')->getDB();
        $files = self::rglob((__DIR__).'/scss/*',GLOB_NOSORT|GLOB_BRACE);
        $files = array_map(function($file){
            $file = str_replace((__DIR__).'/scss/','',$file);
            return $file;
        },$files);
        foreach($files as $file){
            if (!is_dir((__DIR__).'/scss/'.$file)){
                $data = file_get_contents((__DIR__).'/scss/'.$file);
                $type = 'insert ignore';
                if ($replace) $type = 'replace';
                $db->direct($type.'
                    into getbootstrap_scss (
                        filename,
                        content
                    ) values (
                        {filename},
                        {content}
                    )
                ',[
                    'filename'=>$file,
                    'content'=>$data
                ]);
            }
        }

    }
}