let Thumb3x = function (config) {
    config = config || {};
    Thumb3x.superclass.constructor.call(this, config);
};
Ext.extend(Thumb3x, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('thumb3x', Thumb3x);

Thumb3x = new Thumb3x();