<?php

$_lang['thumb3x'] = 'Thumb3x';
$_lang['thumb3x_menu_desc'] = 'Thumb3x - On-the-fly image manipulation';
$_lang['thumb3x_intro_msg'] = 'You can select multiple items by holding Shift or Ctrl.';

// System Settings
$_lang['setting_thumb3x.driver'] = 'Processing Driver';
$_lang['setting_thumb3x.driver_desc'] = 'Select the library for image processing. Imagick is more powerful but must be installed on the server. GD is the standard option.';
$_lang['setting_thumb3x.cache_path'] = 'Cache folder path';
$_lang['setting_thumb3x.cache_path_desc'] = 'Example: assets/components/thumb3x/cache/. An empty value means the default assets/components/thumb3x/cache/. Use a dedicated folder: not the site root, a MODX system folder or a media source folder, otherwise cache clearing is refused.';
$_lang['setting_thumb3x.cache_path_mode'] = 'Cache path scheme';
$_lang['setting_thumb3x.cache_path_mode_desc'] = 'How files in the cache folder are named. source - the standard Glide scheme: the source path is visible in the cache URL (default, behaviour of previous versions). hashed - hash only, spread across subfolders (recommended): the original path is not exposed. flat - hash only, in a single folder. NOTE: after changing this value, run a full cache truncate - previously generated files stay in the old scheme.';

$_lang['setting_thumb3x.cache_file_rights'] = 'Cache File Permissions';
$_lang['setting_thumb3x.cache_file_rights_desc'] = 'Specify permissions in octal format (e.g., 0644) for generated image files.';
$_lang['setting_thumb3x.cache_dir_rights'] = 'Cache Directory Permissions';
$_lang['setting_thumb3x.cache_dir_rights_desc'] = 'Specify permissions in octal format (e.g., 0755) for created directories in the cache.';

$_lang['setting_thumb3x.source_id'] = 'Image Media Source';
$_lang['setting_thumb3x.source_id_desc'] = 'Select the Media Source where your source images are stored. Defaults to "Filesystem".';


$_lang['thumb3x_tab'] = 'Files';
$_lang['thumb3x_preview'] = 'Preview';
$_lang['thumb3x_source_path'] = 'Source File';
$_lang['thumb3x_dimensions'] = 'Dimensions';
$_lang['thumb3x_filesize'] = 'Size';
$_lang['thumb3x_createdon'] = 'Created On';
$_lang['thumb3x_accessedon'] = 'Accessed On';
$_lang['thumb3x_grid_actions'] = 'Actions';


$_lang['thumb3x_search'] = 'Search...';
$_lang['thumb3x_clear'] = 'Clear';


$_lang['thumb3x_truncate_all'] = 'Clear entire cache';
$_lang['thumb3x_truncate'] = 'Truncate Cache';
$_lang['thumb3x_truncate_confirm'] = 'Are you sure you want to permanently delete ALL generated images and clear the log table?';
$_lang['thumb3x_truncate_success'] = 'Cache cleared successfully.';
$_lang['thumb3x_err_cache_path_unsafe'] = 'Clearing cancelled: the cache folder "[[+path]]" is the site root, a MODX system folder or a media source folder, or contains one of them. Set a dedicated folder in the thumb3x.cache_path system setting.';
$_lang['thumb3x_file_remove'] = 'Remove file';
$_lang['thumb3x_files_remove'] = 'Remove files';
$_lang['thumb3x_item_err_ns'] = 'No records selected.';
$_lang['thumb3x_item_err_nf'] = 'Record not found. Refresh the table and try again.';


$_lang['thumb3x_item_remove'] = 'Remove Item';
$_lang['thumb3x_items_remove'] = 'Remove Items';
$_lang['thumb3x_file_remove_confirm'] = 'Are you sure you want to remove this record? This action will also delete the file from the disk.';
$_lang['thumb3x_files_remove_confirm'] = 'Are you sure you want to remove these records? This action will also delete the files from the disk.';

return $_lang;