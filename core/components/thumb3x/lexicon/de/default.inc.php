<?php

$_lang['thumb3x'] = 'Thumb3x';
$_lang['thumb3x_menu_desc'] = 'Thumb3x - On-the-fly Bildbearbeitung';
$_lang['thumb3x_intro_msg'] = 'Sie können mehrere Einträge auswählen, indem Sie Shift oder Strg gedrückt halten.';


$_lang['setting_thumb3x.driver'] = 'Verarbeitungstreiber';
$_lang['setting_thumb3x.driver_desc'] = 'Wählen Sie die Bibliothek für die Bildverarbeitung. Imagick ist leistungsfähiger, muss aber auf dem Server installiert sein. GD ist die Standardoption. Mögliche Optionen: imagick oder gd';
$_lang['setting_thumb3x.cache_path'] = 'Pfad zum Cache-Ordner';
$_lang['setting_thumb3x.cache_path_desc'] = 'Beispiel: assets/components/thumb3x/cache/. Ein leerer Wert bedeutet den Standard assets/components/thumb3x/cache/. Verwenden Sie einen eigenen Ordner: nicht das Website-Stammverzeichnis, keinen MODX-Systemordner und keinen Medienquellen-Ordner, sonst wird das Leeren des Caches abgelehnt.';
$_lang['setting_thumb3x.cache_path_mode'] = 'Schema der Cache-Pfade';
$_lang['setting_thumb3x.cache_path_mode_desc'] = 'Wie Dateien im Cache-Ordner benannt werden. source - das Standardschema von Glide: der Quellpfad ist in der Cache-URL sichtbar (Standardwert, Verhalten früherer Versionen). hashed - nur Hash, auf Unterordner verteilt (empfohlen): der Originalpfad wird nicht preisgegeben. flat - nur Hash, in einem einzigen Ordner. HINWEIS: Nach einer Änderung den Cache vollständig leeren - zuvor erzeugte Dateien bleiben im alten Schema.';


$_lang['thumb3x_tab'] = 'Dateien';

$_lang['thumb3x_preview'] = 'Vorschau';
$_lang['thumb3x_source_path'] = 'Quelldatei';
$_lang['thumb3x_dimensions'] = 'Abmessungen';
$_lang['thumb3x_filesize'] = 'Größe';
$_lang['thumb3x_createdon'] = 'Erstellt';
$_lang['thumb3x_accessedon'] = 'Zuletzt aufgerufen';
$_lang['thumb3x_grid_actions'] = 'Aktionen';


$_lang['thumb3x_search'] = 'Suchen...';
$_lang['thumb3x_clear'] = 'Löschen';

$_lang['thumb3x_truncate_all'] = 'Gesamten Cache leeren';
$_lang['thumb3x_truncate'] = 'Cache vollständig leeren';
$_lang['thumb3x_truncate_confirm'] = 'Sind Sie sicher, dass Sie ALLE generierten Bilder dauerhaft löschen und die Log-Tabelle leeren möchten?';
$_lang['thumb3x_truncate_success'] = 'Cache erfolgreich geleert.';
$_lang['thumb3x_err_cache_path_unsafe'] = 'Leeren abgebrochen: Der Cache-Ordner "[[+path]]" ist das Website-Stammverzeichnis, ein MODX-Systemordner oder ein Medienquellen-Ordner oder enthält einen davon. Legen Sie in der Systemeinstellung thumb3x.cache_path einen eigenen Ordner fest.';
$_lang['thumb3x_file_remove'] = 'Datei entfernen';
$_lang['thumb3x_files_remove'] = 'Dateien entfernen';
$_lang['thumb3x_item_err_ns'] = 'Keine Einträge ausgewählt.';
$_lang['thumb3x_item_err_nf'] = 'Eintrag nicht gefunden. Aktualisieren Sie die Tabelle und versuchen Sie es erneut.';


$_lang['setting_thumb3x.cache_file_rights'] = 'Berechtigungen für Cache-Dateien';
$_lang['setting_thumb3x.cache_file_rights_desc'] = 'Zugriffsrechte im Oktalformat (z.B. 0644) für erstellte Bilddateien.';
$_lang['setting_thumb3x.cache_dir_rights'] = 'Berechtigungen für Cache-Ordner';
$_lang['setting_thumb3x.cache_dir_rights_desc'] = 'Zugriffsrechte im Oktalformat (z.B. 0755) für erstellte Ordner im Cache.';

$_lang['setting_thumb3x.source_id'] = 'Medienquelle für Bilder';
$_lang['setting_thumb3x.source_id_desc'] = 'Wählen Sie die Medienquelle, in der Ihre Originalbilder gespeichert sind. Standardmäßig ist dies "Filesystem".';


$_lang['thumb3x_file_remove_confirm'] = 'Sind Sie sicher, dass Sie diesen Eintrag löschen möchten? Diese Aktion löscht auch die Datei von der Festplatte.';
$_lang['thumb3x_files_remove_confirm'] = 'Sind Sie sicher, dass Sie diese Einträge löschen möchten? Diese Aktion löscht auch die Dateien von der Festplatte.';


$_lang['thumb3x_item_remove'] = 'Eintrag löschen';
$_lang['thumb3x_items_remove'] = 'Einträge löschen';




return $_lang;

