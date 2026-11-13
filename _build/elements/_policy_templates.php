<?php

return [
    'Thumb3xUserPolicyTemplate' => [  //  Уникальное имя ШАБЛОНА
        'description' => 'Thumb3x policy template description.', // Описание ШАБЛОНА
        'template_group' => 1, // Группа шаблонов (обычно 1)
        'lexicon' => 'thumb3x:permissions', //  Укажите ваш лексикон
        'permissions' => [ //  Список РАЗРЕШЕНИЙ (Permissions), которые будут доступны в шаблоне
            [
                'name' => 'thumb3x_save',
                'description' => 'Permission to save Thumb3x data.',  // Описание РАЗРЕШЕНИЯ
                'value' => true,
            ],
            [
                'name' => 'new_user',
                'description' => 'Permission to create new users.', // Описание РАЗРЕШЕНИЯ
                'value' => true,
            ],
        ],
    ],
];
