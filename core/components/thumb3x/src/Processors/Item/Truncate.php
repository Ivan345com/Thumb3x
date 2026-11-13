<?php

namespace Thumb3x\Processors\Item;

use Thumb3x\CachePath;
use Thumb3x\Model\Thumb3xFile;
use MODX\Revolution\Processors\Processor;

class Truncate extends Processor
{
    public $languageTopics = ['thumb3x:default'];
    public $permission = 'empty_cache';

    /**
     * Очистка кэша - то же право, что и у очистки кэша сайта (есть в политиках Administrator и Developer).
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission($this->permission);
    }

    /**
     * @return array|string
     */
    public function process()
    {
        // Путь к кэшу из настроек; удалять что-либо можно только в безопасной папке
        $cachePath = CachePath::absolute($this->modx);
        if (!CachePath::isSafeToClean($this->modx)) {
            return $this->failure($this->modx->lexicon('thumb3x_err_cache_path_unsafe', [
                'path' => CachePath::relative($this->modx),
            ]));
        }
        
        // Опциональный параметр source_id для очистки кэша конкретного источника
        $sourceId = $this->getProperty('source_id', null);
        
        if ($sourceId) {
            // Очистка для конкретного источника
            $tableName = $this->modx->getTableName(Thumb3xFile::class);

            // Удаляем файлы записей этого источника (итератором: записей может быть много)
            $files = $this->modx->getIterator(Thumb3xFile::class, ['source_id' => (int)$sourceId]);
            foreach ($files as $file) {
                $path = (string)$file->get('cache_path');
                if ($path === '') {
                    continue;
                }
                $fullPath = CachePath::fileFromUrl($this->modx, $path);
                if (is_file($fullPath) && CachePath::contains($cachePath, $fullPath) && @unlink($fullPath)) {
                    CachePath::removeEmptyParents($cachePath, $fullPath);
                }
            }

            // Удаляем записи из БД для этого источника
            $this->modx->exec("DELETE FROM {$tableName} WHERE source_id = " . (int)$sourceId);
            
            return $this->success($this->modx->lexicon('thumb3x_truncate_success'));
        } else {
            // Полная очистка всего кэша
            // 1. Очищаем папку с файлами кэша
            if (is_dir($cachePath)) {
                $this->deleteDir($cachePath, false); // Удаляем содержимое, но не саму папку
            }

            // 2. Очищаем таблицу в базе данных
            $tableName = $this->modx->getTableName(Thumb3xFile::class);
            $this->modx->exec("TRUNCATE TABLE {$tableName}");

            return $this->success($this->modx->lexicon('thumb3x_truncate_success'));
        }
    }

    /**
     * Рекурсивно удаляет содержимое директории
     * @param string $dirPath
     * @param bool $deleteMe
     */
    private function deleteDir(string $dirPath, bool $deleteMe = true)
    {
        if (!is_dir($dirPath)) {
            return;
        }
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dirPath, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($iterator as $path) {
            $path->isDir() && !$path->isLink() ? rmdir($path->getPathname()) : unlink($path->getPathname());
        }
        if ($deleteMe) {
            rmdir($dirPath);
        }
    }
}