<?php
namespace Thumb3x\Model\mysql;

use xPDO\xPDO;

class Thumb3xFile extends \Thumb3x\Model\Thumb3xFile
{

    public static $metaMap = array (
        'package' => 'Thumb3x\\Model',
        'version' => '3.0',
        'table' => 'thumb3x_files',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'hash' => '',
            'source_id' => 1,
            'source_path' => NULL,
            'cache_path' => NULL,
            'params' => NULL,
            'cache_filesize' => 0,
            'cache_width' => 0,
            'cache_height' => 0,
            'error_message' => NULL,
            'createdon' => NULL,
            'accessedon' => NULL,
        ),
        'fieldMeta' => 
        array (
            'hash' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '40',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'comment' => 'SHA1 хэш от пути к файлу и параметров',
            ),
            'source_id' => 
            array (
                'dbtype' => 'int',
                'precision' => '10',
                'phptype' => 'integer',
                'attributes' => 'unsigned',
                'null' => false,
                'default' => 1,
                'comment' => 'ID источника файлов (Media Source)',
            ),
            'source_path' => 
            array (
                'dbtype' => 'text',
                'phptype' => 'string',
                'null' => false,
                'comment' => 'Оригинальный путь к файлу',
            ),
            'cache_path' => 
            array (
                'dbtype' => 'text',
                'phptype' => 'string',
                'null' => false,
                'comment' => 'Путь к сгенерированному файлу',
            ),
            'params' => 
            array (
                'dbtype' => 'text',
                'phptype' => 'json',
                'null' => true,
                'comment' => 'Параметры обработки в формате JSON',
            ),
            'cache_filesize' => 
            array (
                'dbtype' => 'int',
                'precision' => '10',
                'phptype' => 'integer',
                'attributes' => 'unsigned',
                'null' => false,
                'default' => 0,
                'comment' => 'Размер файла в байтах',
            ),
            'cache_width' => 
            array (
                'dbtype' => 'smallint',
                'precision' => '5',
                'phptype' => 'integer',
                'attributes' => 'unsigned',
                'null' => false,
                'default' => 0,
                'comment' => 'Ширина сгенерированного изображения',
            ),
            'cache_height' => 
            array (
                'dbtype' => 'smallint',
                'precision' => '5',
                'phptype' => 'integer',
                'attributes' => 'unsigned',
                'null' => false,
                'default' => 0,
                'comment' => 'Высота сгенерированного изображения',
            ),
            'error_message' => 
            array (
                'dbtype' => 'text',
                'phptype' => 'string',
                'null' => true,
                'comment' => 'Сообщение об ошибке, если генерация не удалась',
            ),
            'createdon' => 
            array (
                'dbtype' => 'datetime',
                'phptype' => 'datetime',
                'null' => true,
            ),
            'accessedon' => 
            array (
                'dbtype' => 'datetime',
                'phptype' => 'datetime',
                'null' => true,
                'comment' => 'Время последнего доступа к файлу',
            ),
        ),
        'indexes' => 
        array (
            'hash' => 
            array (
                'alias' => 'hash',
                'primary' => false,
                'unique' => true,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'hash' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'source_id' => 
            array (
                'alias' => 'source_id',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'source_id' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'accessedon' => 
            array (
                'alias' => 'accessedon',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'accessedon' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
        ),
    );

}
