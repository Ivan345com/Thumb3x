<?php

return [
    [
        'key' => 'thumb3x.source_id',
        'xtype' => 'modx-combo-source',
        'value' => 1, // ID источника по умолчанию (1 = Filesystem)
        'area' => 'thumb3x_main',
    ],
    [
        'key' => 'thumb3x.driver',
        'value' => 'gd', //imagick
        'xtype' => 'textfield',
        'area' => 'thumb3x_main',
    ],
    [
        'key' => 'thumb3x.cache_path',
        'value' => 'assets/components/thumb3x/cache/',
        'xtype' => 'textfield',
        'area' => 'thumb3x_main',
    ],
    [
        'key' => 'thumb3x.cache_path_mode',
        'value' => 'source', // source | hashed | flat
        'xtype' => 'textfield',
        'area' => 'thumb3x_main',
    ],

    [
        'key' => 'thumb3x.cache_file_rights',
        'xtype' => 'textfield',
        'value' => '0644',
        'area' => 'thumb3x_permissions',
    ],
    [
        'key' => 'thumb3x.cache_dir_rights',
        'xtype' => 'textfield',
        'value' => '0755',
        'area' => 'thumb3x_permissions',
    ],

];
