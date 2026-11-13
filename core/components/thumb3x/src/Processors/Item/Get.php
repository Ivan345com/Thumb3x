<?php

namespace Thumb3x\Processors\Item;

use MODX\Revolution\Processors\Model\GetProcessor;
use Thumb3x\Model\Thumb3xFile;

class Get extends GetProcessor
{
    public $objectType = 'Thumb3xFile';
    public $classKey = Thumb3xFile::class;
    public $languageTopics = ['thumb3x:default'];
    //public $permission = 'view';


    /**
     * We doing special check of permission
     * because of our objects is not an instances of modAccessibleObject
     *
     * @return mixed
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        return parent::process();
    }


}