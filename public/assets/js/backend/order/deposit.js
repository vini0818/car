define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'order/deposit/index',
                    multi_url: 'order/deposit/multi',
                    refund_url: 'order/deposit/refund',
                    table: 'deposit'
                }
            });

            var table = $("#table");
            //在普通搜索渲染后
            table.on('post-common-search.bs.table', function (event, table) {
                //用户搜索下拉内容（从数据表中读取）
                $("input[name='user_id']", table.$commonsearch).addClass("selectpage").data("source", "user/user/selectpage").data("primaryKey", "id").data("field", "phone").data("orderBy", "id desc");
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
                        {field: 'user_name', title: __('User_name'), operate: false},
                        {field: 'user_id', title: __('User_phone'), formatter: Controller.api.formatter.user_id},
                        {field: 'trade_no', title: __('Trade_no'), operate: false},
                        {field: 'total_fee', title: __('Total_fee'), operate: false},
                        {field: 'create_time', title: __('Create_time'), operate: false},
                        {field: 'pay_time', title: __('Pay_time'), operate: false},
                        {field: 'status', title: __('Status'), searchList: $.getJSON('ajax/dict?name=trade_status'), formatter: Controller.api.formatter.status},
                        {field: 'refund_fee', title: __('Refund_fee'), operate: false},
                        {field: 'refund_create_time', title: __('Refund_create_time'), operate: false},
                        {field: 'refund_end_time', title: __('Refund_end_time'), operate: false},
                        {field: 'refund_status', title: __('Refund_status'), operate: false, formatter: Controller.api.formatter.status},
                        {field: 'memo', title: __('Memo'), operate: false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, buttons: [], formatter: Controller.api.formatter.operate}
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
        refund: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            formatter: {
                user_id: function (value, row, index) {
                    return row.user_phone;
                },
                village_id: function (value, row, index) {
                    return row.village_name;
                },
                status: function (value, row, index) {
                    if (value === null) {
                        return '-';
                    }
                    //颜色状态数组,可使用red/yellow/aqua/blue/navy/teal/olive/lime/fuchsia/purple/maroon
                    var colorArr = {primary: 'grey', locked: 'info', normal: 'success', disabled: 'danger'};
                    var color = value && typeof colorArr[row[this.field + '_color']] !== 'undefined' ? colorArr[row[this.field + '_color']] : 'primary';
                    //渲染状态
                    return '<span class="text-' + color + '"><i class="fa fa-circle"></i> ' + value + '</span>';
                },
                operate: function (value, row, index) {
                    var buttons = $.extend([], this.buttons || []);
                    switch (row.status) {
                        case '交易成功':
                            if(row.refund_status != '退款中' && row.refund_status != '退款成功') {
                                buttons.push({name: 'refund', text: __('Refund'), icon: 'fa fa-yen', classname: 'btn btn-warning btn-xs btn-dialog', url: $.fn.bootstrapTable.defaults.extend.refund_url});
                            }
                            break;
                        default :
                            break;
                    }
                    return Table.api.buttonlink(this, buttons, value, row, index, 'operate');
                }
            }
        }
    };
    return Controller;
});