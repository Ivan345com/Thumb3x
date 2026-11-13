<?php

use MODX\Revolution\modExtraManagerController;

/**
 * The home manager controller for Thumb3x.
 *
 */
class Thumb3xHomeManagerController extends modExtraManagerController
{
    /** @var Thumb3x\Thumb3x $Thumb3x */
    public $Thumb3x;


    /**
     *
     */
    public function initialize()
    {
        $this->Thumb3x = $this->modx->services->get('Thumb3x');
        parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['thumb3x:default'];
    }


    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return true;
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('thumb3x');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->Thumb3x->config['cssUrl'] . 'mgr/main.css');
        $this->addJavascript($this->Thumb3x->config['jsUrl'] . 'mgr/thumb3x.js');
        $this->addJavascript($this->Thumb3x->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->Thumb3x->config['jsUrl'] . 'mgr/misc/combo.js');
        $this->addJavascript($this->Thumb3x->config['jsUrl'] . 'mgr/tab1/items.grid.js');
        $this->addJavascript($this->Thumb3x->config['jsUrl'] . 'mgr/sections/home.panel.js');
        $this->addJavascript($this->Thumb3x->config['jsUrl'] . 'mgr/sections/home.js');

        $this->addHtml('<script type="text/javascript">
        Thumb3x.config = ' . json_encode($this->Thumb3x->config) . ';
        Thumb3x.config.connector_url = "' . $this->Thumb3x->config['connectorUrl'] . '";
        Ext.onReady(function() {MODx.load({ xtype: "thumb3x-page-home"});});
        </script>');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .= '<div id="thumb3x-panel-home-div"></div>';
        return '';
    }
}
