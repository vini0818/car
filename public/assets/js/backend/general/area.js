define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'general/area/index',
                    edit_url: 'general/area/edit',
                    multi_url: 'general/area/multi',
                    table: 'area',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                sortOrder: 'asc',
                pageSize: 17,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate: false},
                        {field: 'pid', title: __('Pid')},
                        {field: 'name', title: __('Name'), formatter: Controller.api.formatter.name, operate: 'LIKE %...%'},
                        {field: 'mergename', title: __('Mergename'), operate: false},
                        {field: 'unit_price', title: __('Unit_price'), formatter: Controller.api.formatter.unit_price, operate: false},
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
                name: function (value, row, index) {
                    //这里手动构造URL
                    var url;
                    if (row.level < 3) {
                        url = "general/area?pid=" + row.id;
                    } else {
                        return value;
                    }
                    //方式一,直接返回class带有addtabsit的链接,这可以方便自定义显示内容
                    return '<a href="' + url + '" class="label label-success addtabsit" title="' + __("Search %s", value) + '">' + __(value) + '</a>';
                    //方式二,直接调用Table.api.formatter.addtabs
//                    return Table.api.formatter.addtabs(value, row, index, url);
                },
                unit_price: function (value, row, index) {
                    return value == 0 ? __('Not set') : value;
                }
            }
        }
    };
    return Controller;
});