Thumb3x.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            cls: 'container',
            xtype: 'thumb3x-panel-home',
            renderTo: 'thumb3x-panel-home-div'
        }]
    });
    Thumb3x.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(Thumb3x.page.Home, MODx.Component);
Ext.reg('thumb3x-page-home', Thumb3x.page.Home);