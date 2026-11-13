Thumb3x.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',

        items: [{
            html: '<h2>' + _('thumb3x') + '</h2>',
            cls: 'container',
            //style: {margin: '15px 10px 10px 10px'}
        }, {
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            style: { marginBottom: '5px' }, // Добавляем отступ снизу от табов
            items: [{
                title: _('thumb3x_tab'),
                layout: 'anchor',
                items: [{
                    html: '',
                    cls: 'panel-desc',
                }, {
                    xtype: 'panel',
                    cls: 'container',
                    items: [{}, {
                        xtype: 'thumb3x-grid-items'
                    }]
                }]
            }]
        }, { // НАЧАЛО БЛОКА С ССЫЛКАМИ
            xtype: 'box', // Используем box component для простого HTML
            autoEl: {
                tag: 'div',
                cls: 'container panel-desc',
                style: 'text-align: center; padding-top: 15px; border-top: 1px solid #f0f0f0;',
                cn: [ // Children nodes (дочерние узлы)
                    {
                        tag: 'a',
                        href: 'https://docs.ivan345.com/books/thumb3x/page/thumb3x-dokumentaciia',
                        html: 'DOC - RUS',
                        target: '_blank',
                        style: 'margin: 0 5px;'
                    },
                    {
                        tag: 'span',
                        html: '|',                // Разделитель
                        style: 'margin: 0 5px; color: #ccc;'
                    },
                    {
                        tag: 'a',
                        href: 'https://docthumb3x.ivan345.com/en/',
                        html: 'DOC - EN',
                        target: '_blank',
                        style: 'margin: 0 5px;'
                    },
                    {
                        tag: 'span',
                        html: '|',
                        style: 'margin: 0 5px; color: #ccc;'
                    },
                    {
                        tag: 'a',
                        href: 'https://glide.thephpleague.com/',
                        html: 'Glide',
                        target: '_blank',
                        style: 'margin: 0 5px;'
                    }
                ]
            }
        } // КОНЕЦ БЛОКА С ССЫЛКАМИ
        ]
    });
    Thumb3x.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(Thumb3x.panel.Home, MODx.Panel);
Ext.reg('thumb3x-panel-home', Thumb3x.panel.Home);