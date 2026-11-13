<?php

namespace Thumb3x\Processors\Item;

use Thumb3x\Model\Thumb3xFile;
use MODX\Revolution\Processors\Model\GetListProcessor;
use xPDO\Om\xPDOQuery;
use xPDO\Om\xPDOObject;

class GetList extends GetListProcessor
{
    public $objectType = 'Thumb3xFile';
    public $classKey = Thumb3xFile::class;
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';

    public function beforeQuery()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }
        return true;
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {

        $query = trim($this->getProperty('query'));

        if (!empty($query)) {

            $c->where([
                'source_path:LIKE' => "%{$query}%",
                'OR:cache_path:LIKE' => "%{$query}%",
            ]);
        }


        return $c;
    }

    /**
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $array = $object->toArray();

        // Код для 'actions'
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('thumb3x_item_remove'),
            'multiple' => $this->modx->lexicon('thumb3x_items_remove'),
            'action' => 'removeItem',
            'button' => true,
            'menu' => true,
        ];

        return $array;
    }
}