Thumb3x.grid.Items = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'thumb3x-grid-items';
    }
    Ext.applyIf(config, {
        url: Thumb3x.config.connector_url,
        fields: this.getFields(config),
        columns: this.getColumns(config),
        tbar: this.getTopBar(config),
        sm: new Ext.grid.CheckboxSelectionModel(),
        baseParams: {
            action: 'Thumb3x\\Processors\\Item\\GetList',
        },
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            autoFill: true,
            showPreview: true,
            scrollOffset: 0
        },
        paging: true,
        remoteSort: true,
        autoHeight: true,
    });
    Thumb3x.grid.Items.superclass.constructor.call(this, config);
};
Ext.extend(Thumb3x.grid.Items, MODx.grid.Grid, {
    windows: {},

    getMenu: function (grid, rowIndex) {
        const row = grid.getStore().getAt(rowIndex);
        const menu = Thumb3x.utils.getMenu(row.data.actions, this, row.data.id);
        this.addContextMenuItem(menu);
    },

    removeItem: function () {
        const ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.msg.confirm({
            title: ids.length > 1 ? _('thumb3x_files_remove') : _('thumb3x_file_remove'),
            text: ids.length > 1 ? _('thumb3x_files_remove_confirm') : _('thumb3x_file_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'Thumb3x\\Processors\\Item\\Remove',
                // ++ ИСПРАВЛЕНО: Возвращаем кодирование в JSON, как того требует ваш процессор ++
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () { this.refresh(); },
                    scope: this
                }
            }
        });
        return true;
    },

    // ++ НАЧАЛО: НОВЫЙ МЕТОД ДЛЯ ПОЛНОЙ ОЧИСТКИ ++
    truncate: function (btn, e) {
        MODx.msg.confirm({
            title: _('thumb3x_truncate_all'),
            text: _('thumb3x_truncate_confirm'),
            url: this.config.url,
            params: {
                action: 'Thumb3x\\Processors\\Item\\Truncate'
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
    },
    // ++ КОНЕЦ: НОВЫЙ МЕТОД ДЛЯ ПОЛНОЙ ОЧИСТКИ ++

    getFields: function () {
        return [
            'id', 'source_path', 'cache_path', 'params', 'cache_filesize',
            'cache_width', 'cache_height', 'createdon', 'accessedon', 'actions'
        ];
    },

    getColumns: function () {
        return [{
            header: _('id'),
            dataIndex: 'id',
            sortable: true,
            hidden: true
        }, {
            header: _('thumb3x_preview'),
            dataIndex: 'cache_path',
            sortable: false,
            width: 100,
            renderer: function(value) {
                if (value) {
                    // Путь берётся из БД - экранируем, чтобы он не мог вставить в страницу свой HTML
                    const fullUrl = Ext.util.Format.htmlEncode(MODx.config.base_url + value);
                    return '<a href="' + fullUrl + '" target="_blank" rel="noopener"><img src="' + fullUrl + '" alt="" style="height: 50px; max-width: 90px; object-fit: contain;"></a>';
                }
                return '';
            }
        }, {
            header: _('thumb3x_source_path'),
            dataIndex: 'source_path',
            sortable: true,
            width: 250,
            renderer: function(value) {
                // Имя исходного файла может содержать HTML - выводим как текст
                return Ext.util.Format.htmlEncode(value);
            }
        }, {
            header: _('thumb3x_dimensions'),
            dataIndex: 'cache_width',
            sortable: true,
            width: 100,
            renderer: function(value, meta, record) {
                return record.data.cache_width + 'x' + record.data.cache_height;
            }
        }, {
            header: _('thumb3x_filesize'),
            dataIndex: 'cache_filesize',
            sortable: true,
            width: 100,
            renderer: function(value) {
                // Преобразуем значение в целое число (integer)
                const size = parseInt(value, 10);

                if (size === 0 || typeof Thumb3x.utils.formatFileSize !== 'function') {
                    return '0 B';
                }

                return Thumb3x.utils.formatFileSize(size);
            }
        }, {
            header: _('thumb3x_createdon'),
            dataIndex: 'createdon',
            sortable: true,
            width: 150,
            renderer: Ext.util.Format.dateRenderer(MODx.config.manager_date_format + ' ' + MODx.config.manager_time_format)
        }, {
            header: _('thumb3x_accessedon'),
            dataIndex: 'accessedon',
            sortable: true,
            width: 150,
            renderer: Ext.util.Format.dateRenderer(MODx.config.manager_date_format + ' ' + MODx.config.manager_time_format)
        }, {
            header: _('thumb3x_grid_actions'),
            dataIndex: 'actions',
            renderer: Thumb3x.utils.renderActions,
            sortable: false,
            width: 100,
            id: 'actions'
        }];
    },

    //
    getTopBar: function () {
        return [{
            text: '<i class="icon icon-trash"></i> ' + _('thumb3x_truncate_all'),
            handler: this.truncate,
            scope: this
        }, '->', {
            xtype: 'textfield',
            name: 'query',
            emptyText: _('thumb3x_search'),
            listeners: {
                change: { fn: this._doSearch, scope: this },
                render: { fn: function(cmp) {
                        new Ext.KeyMap(cmp.getEl(), {
                            key: Ext.EventObject.ENTER,
                            fn: function() {
                                this.fireEvent('change', this);
                                this.blur();
                                return true;
                            },
                            scope: cmp
                        });
                    }, scope: this }
            }
        }, {
            xtype: 'button',
            text: _('thumb3x_clear'),
            listeners: {
                click: {
                    fn: function(btn) {
                        const searchField = btn.ownerCt.find('name', 'query')[0];
                        this._clearSearch(searchField);
                    },
                    scope: this
                }
            }
        }];
    },
    //

    onClick: function (e) {
        const elem = e.getTarget();
        if (elem.nodeName === 'BUTTON') {
            const row = this.getSelectionModel().getSelected();
            if (typeof (row) != 'undefined') {
                const action = elem.getAttribute('action');
                if (action === 'showMenu') {
                    const ri = this.getStore().find('id', row.id);
                    return this._showMenu(this, ri, e);
                } else if (typeof this[action] === 'function') {
                    this.menu.record = row.data;
                    return this[action](this, e);
                }
            }
        }
        return this.processEvent('click', e);
    },

    _getSelectedIds: function () {
        const ids = [];
        const selected = this.getSelectionModel().getSelections();
        for (const i in selected) {
            if (!selected.hasOwnProperty(i)) {
                continue;
            }
            ids.push(selected[i]['id']);
        }
        return ids;
    },

    _doSearch: function (tf) {
        this.getStore().baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
    },

    _clearSearch: function (tf) {
        if (tf) {
            tf.setValue('');
        }
        this.getStore().baseParams.query = '';
        this.getBottomToolbar().changePage(1);
    },
});
Ext.reg('thumb3x-grid-items', Thumb3x.grid.Items);