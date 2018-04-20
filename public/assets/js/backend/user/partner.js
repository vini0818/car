define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'user/partner/index',
                    add_url: 'user/partner/add',
                    edit_url: 'user/partner/edit',
                    del_url: 'user/partner/del',
                    multi_url: 'user/partner/multi',
                    table: 'partner',
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
                        {field: 'name', title: __('Name'), operate: 'LIKE %...%'},
                        {field: 'type', title: __('Type'), searchList: $.getJSON('ajax/dict?name=partner_type'), formatter: Controller.api.formatter.type},
                        {field: 'memo', title: __('Memo'), operate: false},
                        {field: 'create_time', title: __('Create_time'), operate: false},
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
                }
            }
        }
    };
    return Controller;
});