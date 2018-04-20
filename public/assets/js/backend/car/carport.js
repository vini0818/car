define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'car/carport/index',
                    add_url: 'car/carport/add',
                    edit_url: 'car/carport/edit',
                    del_url: 'car/carport/del',
                    multi_url: 'car/carport/multi',
                    table: 'carport',
                    enable_url: 'car/carport/setEnable',
                    disable_url: 'car/carport/setDisable'
                }
            });

            var table = $("#table");
            //在普通搜索渲染后
            table.on('post-common-search.bs.table', function (event, table) {
                //合伙人搜索下拉内容（从数据表中读取）
                $("input[name='village_id']", table.$commonsearch).addClass("selectpage").data("source", "village/selectpage").data("primaryKey", "id").data("field", "name").data("orderBy", "id desc");
                //selectpage类型的事件注册（绑定selectpage元素事件）
                Form.events.selectpage($("form", table.$commonsearch));
            });

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate: false},
                        {field: 'gprs_id', title: __('Gprs_id')},
                        {field: 'village_id', title: __('Village_id'), formatter: Controller.api.formatter.village_id},
                        {field: 'electricity', title: __('Electricity'), operate: false},
                        {field: 'flag', title: __('Flag'), formatter: Table.api.formatter.flag, operate: false},
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
                village_id: function (value, row, index) {
                    return row.village_name;
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