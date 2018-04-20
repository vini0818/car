define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'income/rechargerecord/index',
                    add_url: 'income/rechargerecord/add',
                    table: 'recharge_record',
                }
            });

            var table = $("#table");
            //在普通搜索渲染后
            table.on('post-common-search.bs.table', function (event, table) {
                //用户搜索下拉内容（从数据表中读取）
                $("input[name='user_id']", table.$commonsearch).addClass("selectpage").data("source", "user/user/selectpage").data("primaryKey", "id").data("field", "phone").data("orderBy", "id desc");
                //cxselect类型的事件注册（绑定cxselect元素事件）
                Form.events.cxselect($("form", table.$commonsearch));
                //selectpage类型的事件注册（绑定selectpage元素事件）
                Form.events.selectpage($("form", table.$commonsearch));
            });

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                search: false, //快速搜索设置
                showToggle: false, //显示切换设置
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate: false},
                        {field: 'user_id', title: __('User_phone'), formatter: Controller.api.formatter.user_id},
                        {field: 'trade_no', title: __('Trade_no')},
                        {field: 'recharge_card_name', title: __('Recharge_card_name'), operate: false},
                        {field: 'fee', title: __('Fee'), operate: false},
                        {field: 'remain_fee', title: __('Remain_fee'), operate: false},
                        {field: 'extra_fee', title: __('Extra_fee'), operate: false},
                        {field: 'remain_extra_fee', title: __('Remain_extra_fee'), operate: false},
                        {field: 'create_time', title: __('Create_time'), operate: false}
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
                user_id: function (value, row, index) {
                    return row.user_phone;
                }
            }
        }
    };
    return Controller;
});