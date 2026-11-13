<?php

$_lang['thumb3x'] = 'Thumb3x';
$_lang['thumb3x_menu_desc'] = 'Thumb3x - нарезка картинок ';
$_lang['thumb3x_intro_msg'] = 'Вы можете выделять сразу несколько записей при помощи Shift или Ctrl.';


$_lang['setting_thumb3x.driver'] = 'Драйвер обработки';
$_lang['setting_thumb3x.driver_desc'] = 'Выберите библиотеку для обработки изображений. imagick более мощный, но должен быть установлен на сервере. gd является стандартным вариантом. Возможные варианты imagick или gd';
$_lang['setting_thumb3x.cache_path'] = 'Путь до папки кэша';
$_lang['setting_thumb3x.cache_path_desc'] = 'Пример: assets/components/thumb3x/cache/. Пустое значение - путь по умолчанию assets/components/thumb3x/cache/. Папка должна быть отдельной: не корень сайта, не системная папка MODX и не папка медиаисточника, иначе очистка кэша будет отменена.';
$_lang['setting_thumb3x.cache_path_mode'] = 'Схема путей в кэше';
$_lang['setting_thumb3x.cache_path_mode_desc'] = 'Как называть файлы в папке кэша. source - штатная схема Glide: путь к исходнику виден в URL кэша (значение по умолчанию, поведение прежних версий). hashed - только хэш, разложенный по подпапкам (рекомендуется): путь к оригиналу не раскрывается. flat - только хэш, одной папкой. ВНИМАНИЕ: после смены значения выполните полную очистку кэша - ранее созданные файлы останутся в старой схеме.';


$_lang['thumb3x_tab'] = 'Файлы';

$_lang['thumb3x_preview'] = 'Превью';
$_lang['thumb3x_source_path'] = 'Исходный файл';
$_lang['thumb3x_dimensions'] = 'Размеры';
$_lang['thumb3x_filesize'] = 'Размер';
$_lang['thumb3x_createdon'] = 'Создан';
$_lang['thumb3x_accessedon'] = 'Просмотрен';
$_lang['thumb3x_grid_actions'] = 'Действия';


$_lang['thumb3x_search'] = 'Поиск...';
$_lang['thumb3x_clear'] = 'Очистить';

$_lang['thumb3x_truncate_all'] = 'Очистить весь кэш';
$_lang['thumb3x_truncate'] = 'Полная очистка кэша';
$_lang['thumb3x_truncate_confirm'] = 'Вы уверены, что хотите безвозвратно удалить ВСЕ сгенерированные изображения и очистить таблицу логов?';
$_lang['thumb3x_truncate_success'] = 'Кэш успешно очищен.';
$_lang['thumb3x_err_cache_path_unsafe'] = 'Очистка отменена: папка кэша «[[+path]]» совпадает с корнем сайта, системной папкой MODX или папкой медиаисточника либо содержит их. Укажите отдельную папку в системной настройке thumb3x.cache_path.';
$_lang['thumb3x_file_remove'] = 'Удалить файл';
$_lang['thumb3x_files_remove'] = 'Удалить файлы';
$_lang['thumb3x_item_err_ns'] = 'Не выбрано ни одной записи.';
$_lang['thumb3x_item_err_nf'] = 'Запись не найдена. Обновите таблицу и повторите.';


$_lang['setting_thumb3x.cache_file_rights'] = 'Права на файлы кэша';
$_lang['setting_thumb3x.cache_file_rights_desc'] = 'Права доступа в восьмеричном формате (например, 0644) для создаваемых файлов изображений.';
$_lang['setting_thumb3x.cache_dir_rights'] = 'Права на папки кэша';
$_lang['setting_thumb3x.cache_dir_rights_desc'] = 'Права доступа в восьмеричном формате (например, 0755) для создаваемых папок в кэше.';

$_lang['setting_thumb3x.source_id'] = 'Источник файлов для изображений';
$_lang['setting_thumb3x.source_id_desc'] = 'Выберите источник файлов, в котором хранятся ваши исходные изображения. По умолчанию это "Filesystem".';


$_lang['thumb3x_file_remove_confirm'] = 'Вы уверены, что хотите удалить эту запись? Это действие удалит сам файл с диска.';
$_lang['thumb3x_files_remove_confirm'] = 'Вы уверены, что хотите удалить эти записи? Это действие удалит сами файлы с диска.';


$_lang['thumb3x_item_remove'] = 'Удалить запись';
$_lang['thumb3x_items_remove'] = 'Удалить записи';




return $_lang;