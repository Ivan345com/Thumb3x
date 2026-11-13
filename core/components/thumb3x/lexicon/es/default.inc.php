<?php

$_lang['thumb3x'] = 'Thumb3x';
$_lang['thumb3x_menu_desc'] = 'Thumb3x - Manipulación de imágenes al vuelo';
$_lang['thumb3x_intro_msg'] = 'Puede seleccionar varios elementos manteniendo presionado Shift o Ctrl.';


$_lang['setting_thumb3x.driver'] = 'Controlador de procesamiento';
$_lang['setting_thumb3x.driver_desc'] = 'Seleccione la biblioteca para el procesamiento de imágenes. Imagick es más potente pero debe estar instalado en el servidor. GD es la opción estándar. Opciones posibles: imagick o gd';
$_lang['setting_thumb3x.cache_path'] = 'Ruta a la carpeta de caché';
$_lang['setting_thumb3x.cache_path_desc'] = 'Ejemplo: assets/components/thumb3x/cache/. Un valor vacío equivale a la ruta por defecto assets/components/thumb3x/cache/. Use una carpeta propia: no la raíz del sitio, ni una carpeta del sistema de MODX, ni la carpeta de una fuente de medios; de lo contrario se rechazará la limpieza de la caché.';
$_lang['setting_thumb3x.cache_path_mode'] = 'Esquema de rutas de caché';
$_lang['setting_thumb3x.cache_path_mode_desc'] = 'Cómo se nombran los archivos en la carpeta de caché. source - el esquema estándar de Glide: la ruta del origen es visible en la URL de caché (valor por defecto, comportamiento de versiones anteriores). hashed - solo el hash, repartido en subcarpetas (recomendado): la ruta del original no queda expuesta. flat - solo el hash, en una única carpeta. AVISO: tras cambiar el valor, vacíe por completo la caché - los archivos ya generados permanecen con el esquema anterior.';


$_lang['thumb3x_tab'] = 'Archivos';

$_lang['thumb3x_preview'] = 'Vista previa';
$_lang['thumb3x_source_path'] = 'Archivo fuente';
$_lang['thumb3x_dimensions'] = 'Dimensiones';
$_lang['thumb3x_filesize'] = 'Tamaño';
$_lang['thumb3x_createdon'] = 'Creado';
$_lang['thumb3x_accessedon'] = 'Último acceso';
$_lang['thumb3x_grid_actions'] = 'Acciones';


$_lang['thumb3x_search'] = 'Buscar...';
$_lang['thumb3x_clear'] = 'Limpiar';

$_lang['thumb3x_truncate_all'] = 'Limpiar toda la caché';
$_lang['thumb3x_truncate'] = 'Limpieza completa de caché';
$_lang['thumb3x_truncate_confirm'] = '¿Está seguro de que desea eliminar permanentemente TODAS las imágenes generadas y limpiar la tabla de registros?';
$_lang['thumb3x_truncate_success'] = 'Caché limpiada exitosamente.';
$_lang['thumb3x_err_cache_path_unsafe'] = 'Limpieza cancelada: la carpeta de caché "[[+path]]" es la raíz del sitio, una carpeta del sistema de MODX o la carpeta de una fuente de medios, o contiene alguna de ellas. Indique una carpeta propia en el ajuste del sistema thumb3x.cache_path.';
$_lang['thumb3x_file_remove'] = 'Eliminar archivo';
$_lang['thumb3x_files_remove'] = 'Eliminar archivos';
$_lang['thumb3x_item_err_ns'] = 'No se ha seleccionado ningún registro.';
$_lang['thumb3x_item_err_nf'] = 'Registro no encontrado. Actualice la tabla e inténtelo de nuevo.';


$_lang['setting_thumb3x.cache_file_rights'] = 'Permisos de archivos de caché';
$_lang['setting_thumb3x.cache_file_rights_desc'] = 'Permisos de acceso en formato octal (por ejemplo, 0644) para los archivos de imagen creados.';
$_lang['setting_thumb3x.cache_dir_rights'] = 'Permisos de carpetas de caché';
$_lang['setting_thumb3x.cache_dir_rights_desc'] = 'Permisos de acceso en formato octal (por ejemplo, 0755) para las carpetas creadas en la caché.';

$_lang['setting_thumb3x.source_id'] = 'Fuente de archivos para imágenes';
$_lang['setting_thumb3x.source_id_desc'] = 'Seleccione la fuente de medios donde se almacenan sus imágenes originales. Por defecto es "Filesystem".';


$_lang['thumb3x_file_remove_confirm'] = '¿Está seguro de que desea eliminar este registro? Esta acción también eliminará el archivo del disco.';
$_lang['thumb3x_files_remove_confirm'] = '¿Está seguro de que desea eliminar estos registros? Esta acción también eliminará los archivos del disco.';


$_lang['thumb3x_item_remove'] = 'Eliminar registro';
$_lang['thumb3x_items_remove'] = 'Eliminar registros';




return $_lang;

