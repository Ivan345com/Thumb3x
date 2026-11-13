<?php

namespace Thumb3x\Processors\Item;

use Thumb3x\CachePath;
use Thumb3x\Model\Thumb3xFile;
use MODX\Revolution\Processors\Processor;

class Remove extends Processor
{
    public $objectType = 'Thumb3xFile';
    public $classKey = Thumb3xFile::class;
    public $languageTopics = ['thumb3x:default'];
    public $permission = 'empty_cache';

    /**
     * Удаление файлов кэша - то же право, что и у очистки кэша сайта.
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
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        $ids = json_decode((string)$this->getProperty('ids'), true);
        $ids = is_array($ids) ? array_values(array_unique(array_filter(array_map('intval', $ids)))) : [];
        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('thumb3x_item_err_ns'));
        }

        // Удалять файлы и папки можно только внутри безопасной папки кэша
        if (!CachePath::isSafeToClean($this->modx)) {
            return $this->failure($this->modx->lexicon('thumb3x_err_cache_path_unsafe', [
                'path' => CachePath::relative($this->modx),
            ]));
        }
        $cacheRoot = CachePath::absolute($this->modx);

        // Сначала находим все записи: если какой-то нет, ничего не удаляем,
        // чтобы не было частичного удаления с ответом "ошибка"
        $objects = [];
        foreach ($ids as $id) {
            /** @var Thumb3xFile $object */
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('thumb3x_item_err_nf'));
            }
            $objects[] = $object;
        }

        foreach ($objects as $object) {
            // Удаляем файл с диска
            $path = (string)$object->get('cache_path');
            if ($path !== '') {
                $fullPath = CachePath::fileFromUrl($this->modx, $path);

                // Файл вне папки кэша (запись из старой схемы или чужой путь) не трогаем
                if (is_file($fullPath) && CachePath::contains($cacheRoot, $fullPath) && @unlink($fullPath)) {
                    CachePath::removeEmptyParents($cacheRoot, $fullPath);
                }
            }

            // Удаляем запись из базы данных
            $object->remove();
        }

        return $this->success();
    }
}