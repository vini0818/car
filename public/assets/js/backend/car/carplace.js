define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'car/carplace/index',
                    add_url: 'car/carplace/add',
                    edit_url: 'car/carplace/edit',
                    del_url: 'car/carplace/del',
                    multi_url: 'car/carplace/multi',
                    table: 'carplace',
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
                        {field: 'place_code', title: __('Place_code')},
                        {field: 'gprs_id', title: __('Gprs_id')},
                        {field: 'door_id', title: __('Door_id')},
                        {field: 'flag', title: __('Flag'), formatter: Table.api.formatter.flag, operate: false},
                        {field: 'is_using', title: __('Is_using'), formatter: Controller.api.formatter.is_using, operate: false},
                        {field: 'status', title: __('Status'), searchList: $.getJSON('ajax/dict?name=status&status=1'), formatter: Table.api.formatter.status},
                        {field: 'status', title: __('Enable/Disable'), formatter: Controller.api.formatter.status, operate: false},
                        {field: 'enable_time', title: __('Enable_time'), operate: false},
                        {field: 'disable_time', title: __('Disable_time'), operate: false},
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
                is_using: function (value, row, index) {
                    return value === '1' ? __('Using') : __('Not use');
                },
                status: function (value, row, index) {
                    return "<a href='javascript:;' class='btn btn-" + (value !== 'normal' ? "success" : "danger") + " btn-xs btn-change' data-id='"
                            + row.id + "' data-params='status=" + (value !== 'normal' ? '1' : '2') + "'>" + (value !== 'normal' ? __('Enable') : __('Disable')) + "</a>";
                }
            }
        }
    };
    return Controller;
});