define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'income/rechargecard/index',
                    add_url: 'income/rechargecard/add',
                    multi_url: 'income/rechargecard/multi',
                    table: 'recharge_card'
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                search: false, //快速搜索设置
                showToggle: false, //显示切换设置
                commonSearch: false, //通用搜索设置
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate: false},
                        {field: 'name', title: __('Name'), operate: false},
                        {field: 'fee', title: __('Fee'), operate: false},
                        {field: 'extra_fee', title: __('Extra_fee'), operate: false},
                        {field: 'number', title: __('Number'), operate: false},
                        {field: 'buy_number', title: __('Buy_number'), operate: false},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status},
                        {field: 'enable_time', title: __('Enable_time'), operate: false},
                        {field: 'disable_time', title: __('Disable_time'), operate: false},
                        {field: 'create_time', title: __('Create_time'), operate: false},
                        {field: 'status', title: __('Operate'), formatter: Controller.api.formatter.status, operate: false}
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
                status: function (value, row, index) {
                    switch (value) {
                        case 'primary':
                        case 'disabled':
                            return "<a href='javascript:;' class='btn btn-danger btn-xs btn-change' data-id='" + row.id + "' data-params='status=1'>" + __('Set to enable') + "</a>";
                            break;
                        case 'normal':
                            return "<a href='javascript:;' class='btn btn-success btn-xs btn-change' data-id='" + row.id + "' data-params='status=2'>" + __('Set to disable') + "</a>";
                            break;
                        default :
                            return '';
                    }
                }
            }
        }
    };
    return Controller;
});