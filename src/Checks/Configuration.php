<?php
namespace Tualo\Office\Bootstrap\Checks;

use Tualo\Office\Basic\Middleware\Session;
use Tualo\Office\Basic\PostCheck;
use Tualo\Office\Basic\TualoApplication as App;


class Configuration  extends PostCheck {
    
    public static function test(array $config){
        $clientdb = App::get('clientDB');
        if (is_null($clientdb)) return;

        if(($cmd = App::configuration('scss','cmd',false))==false){
            PostCheck::formatPrintLn(['red'],"\tscss cmd not found");
            PostCheck::formatPrintLn(['blue'],"\tcall `./tm configuration --section scss --key cmd --value $(which sass)`");
        }else{
            exec($cmd.' --version',$output,$return_var);
            if ($return_var!=0){
                PostCheck::formatPrintLn(['red'],"\tscss cmd *$cmd* is not callable ($return_var), try `npm install -g sass`");
            }else{
                PostCheck::formatPrintLn(['green'],"\tscss vesrion: ".implode(' ',$output));
            }
        }

        $tables = [
            'ds'=>[
                'columns'=>[
                    'table_name'=>'varchar(128)'
                ]
            ],
            'ds_column'=>[],
            'ds_column_list_label'=>[],
            'ds_column_form_label'=>[],
            'view_ds_column'=>['filename'=>'text', 'js'=>'longtext', 'name'=>'varchar(100)', 'table_name'=>'varchar(128)'],
            'view_ds_combobox'=>['filename'=>'text', 'js'=>'longtext', 'name'=>'varchar(100)', 'table_name'=>'varchar(128)'],
            'view_ds_controller'=>['filename'=>'text', 'js'=>'longtext', 'jsx'=>'longtext', 'table_name'=>'varchar(128)'],
            'view_ds_dsview'=>['filename'=>'text', 'js'=>'longtext', 'table_name'=>'varchar(128)'],
            'view_ds_form'=>['filename'=>'text', 'js'=>'longtext', 'jsx'=>'longtext', 'table_name'=>'varchar(128)'],
            'view_ds_list'=>['filename'=>'text', 'js'=>'longtext', 'jsx'=>'longtext', 'table_name'=>'varchar(128)'],
            'view_ds_listcolumn'=>['js'=>'longtext', 'requiresJS'=>'longtext', 'table_name'=>'varchar(128)'],
            'view_ds_model'=>['filename'=>'text', 'js'=>'longtext', 'name'=>'text', 'table_name'=>'varchar(128)'],
            'view_ds_store'=>['filename'=>'text', 'js'=>'longtext', 'name'=>'text', 'table_name'=>'varchar(128)']
        ];
        self::tableCheck('ds',$tables,
            "please run the following command: `./tm install-sql-ds --client ".$clientdb->dbname."`",
            "please run the following command: `./tm install-sql-ds --client ".$clientdb->dbname."`"

        );
    }
}