define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'general/version/index',
                    add_url: 'general/version/add',
                    edit_url: 'general/version/edit',
                    del_url: 'general/version/del',
                    multi_url: 'general/version/multi',
                    table: 'version',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate: false},
                        {field: 'version', title: __('Version'), operate: 'LIKE %...%'},
                        {field: 'os_name', title: __('Os'), searchList: $.getJSON('ajax/dict?name=app_os')},
                        {field: 'download_url', title: __('Download_url'), formatter: Table.api.formatter.url, operate: false},
                        {field: 'is_must', title: __('Is_must'), formatter: Controller.api.formatter.is_must, operate: false},
                        {field: 'create_time', title: __('Create_time'), operate: 'RANGE', addclass: 'datetimerange'},
                        {field: 'update_time', title: __('Update_time'), operate: false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            formatter: {
                is_must: function (value, row, index) {
                    return value === '1' ? __('Yes') : __('No');
                }
            }
        }
    };
    return Controller;
});