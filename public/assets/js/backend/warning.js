define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'warning/index',
                    edit_url: 'warning/edit',
                    multi_url: 'warning/multi',
                    table: 'warning',
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
                        {field: 'type', title: __('Type'), searchList: $.getJSON('ajax/dict?name=warn_type'), formatter: Controller.api.formatter.type},
                        {field: 'title', title: __('Title'), operate: false},
                        {field: 'is_read', title: __('Is_read'), formatter: Controller.api.formatter.is_read},
                        {field: 'read_time', title: __('Read_time'), operate: 'RANGE', addclass: 'datetimerange'},
                        {field: 'create_time', title: __('Create_time'), operate: 'RANGE', addclass: 'datetimerange'},
                        {field: 'update_time', title: __('Update_time'), operate: false, addclass: 'datetimerange'},
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
                type: function (value, row, index) {
                    return row.type_name;
                },
                is_read: function (value, row, index) {
                    return value === '1' ? __('Yes') : __('No');
                }
            }
        }
    };
    return Controller;
});