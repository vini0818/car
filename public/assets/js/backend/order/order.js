define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'order/order/index',
                    multi_url: 'order/order/multi',
                    edit_url: 'order/order/edit',
                    close_url: 'order/order/close', //关闭订单
                    refund_url: 'order/order/refund', //订单退款
                    detail_url: 'order/order/detail',
                    table: 'order'
                }
            });

            var table = $("#table");
            //在普通搜索渲染后
            table.on('post-common-search.bs.table', function (event, table) {
                //用户搜索下拉内容（从数据表中读取）
                $("input[name='user_id']", table.$commonsearch).addClass("selectpage").data("source", "user/user/selectpage").data("primaryKey", "id").data("field", "phone").data("orderBy", "id desc");
                //收取搜索下拉内容（从数据表中读取）
                $("input[name='village_id']", table.$commonsearch).addClass("selectpage").data("source", "village/selectpage").data("primaryKey", "id").data("field", "name").data("orderBy", "id desc");
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
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate: false},
                        {field: 'user_name', title: __('User_name'), operate: false},
                        {field: 'user_id', title: __('User_phone'), formatter: Controller.api.formatter.user_id},
                        {field: 'village_id', title: __('Village_name'), formatter: Controller.api.formatter.village_id},
                        {field: 'order_no', title: __('Order_no')},
                        {field: 'door_id', title: __('Door_id'), operate: false},
                        {field: 'car_id', title: __('Car_id'), operate: false},
                        {field: 'start_time', title: __('Start_time'), operate: 'RANGE', addclass: 'datetimerange'},
                        {field: 'end_time', title: __('End_time'), operate: false},
                        {field: 'duration', title: __('Duration'), operate: false},
                        {field: 'total_fee', title: __('Total_fee'), operate: false},
                        {field: 'cash_fee', title: __('Cash_fee'), operate: false},
                        {field: 'balance_fee', title: __('Balance_fee'), operate: false},
                        {field: 'status', title: __('Status'), searchList: $.getJSON('ajax/dict?name=order_status'), formatter: Controller.api.formatter.status},
                        {field: 'operate', title: __('Operate'), table: table, events: Controller.api.events.operate, buttons: [], formatter: Controller.api.formatter.operate}
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
            var max = $("#c-balance_fee").val();
            $("#c-total_fee").bind('input propertychange', function () {
                var cashInput = $("#c-cash_fee"), balanceInput = $("#c-balance_fee");
                var a = $(this).val();
                if (a <= max) {
                    balanceInput.val(a);
                    cashInput.val('0.00');
                } else {
                    cashInput.val(a - max);
                }
            });
        },
        //为refund页面的form表单添加绑定事件
        refund: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            // 单元格元素事件
            events: {
                operate: {
                    //强制关闭订单事件：有操作提示
                    'click .btn-closeone': function (e, value, row, index) {
                        e.stopPropagation();
                        e.preventDefault();
                        var that = this;
                        var top = $(that).offset().top - $(window).scrollTop();
                        var left = $(that).offset().left - $(window).scrollLeft() - 260;
                        if (top + 154 > $(window).height()) {
                            top = top - 154;
                        }
                        if ($(window).width() < 480) {
                            top = left = undefined;
                        }
                        Layer.confirm(
                                __('Are you sure you want to close this item?'),
                                {icon: 3, title: __('Warning'), offset: [top, left], shadeClose: true},
                                function (index) {
                                    var table = $(that).closest('table');
                                    var ids = row.order_no;
                                    var options = {url: $.fn.bootstrapTable.defaults.extend.close_url, data: {action: 'close', ids: ids}};
                                    Fast.api.ajax(options, function (data) {
                                        table.bootstrapTable('refresh');
                                    });
                                    Layer.close(index);
                                }
                        );
                    }
                }
            },
            formatter: {
                user_id: function (value, row, index) {
                    return row.user_phone;
                },
                village_id: function (value, row, index) {
                    return row.village_name;
                },
                status: function (value, row, index) {
                    //颜色状态数组,可使用red/yellow/aqua/blue/navy/teal/olive/lime/fuchsia/purple/maroon
                    var colorArr = {primary: 'grey', locked: 'info', normal: 'success', disabled: 'danger'};
                    var color = value && typeof colorArr[row[this.field + '_color']] !== 'undefined' ? colorArr[row[this.field + '_color']] : 'primary';
                    //渲染状态
                    return '<span class="text-' + color + '"><i class="fa fa-circle"></i> ' + value + '</span>';
                },
                operate: function (value, row, index) {
                    var buttons = $.extend([], this.buttons || []);
                    switch (row.status) {
                        case '使用中':
                            buttons.push({name: 'close', text: __('Close'), title: __('Close title'), icon: 'fa fa-stop-circle-o', classname: 'btn btn-xs btn-danger btn-closeone'});
                            break;
                        case '待支付':
                            buttons.push({name: 'edit', title: __('Edit'), icon: 'fa fa-pencil', classname: 'btn btn-xs btn-success btn-dialog btn-editone', url: $.fn.bootstrapTable.defaults.extend.edit_url});
                            break;
                        case '已完成':
                            buttons.push({name: 'refund', text: __('Refund'), icon: 'fa fa-yen', classname: 'btn btn-xs btn-warning btn-dialog', url: $.fn.bootstrapTable.defaults.extend.refund_url});
                            break;
                        case '已退款':
                            buttons.push({name: 'detail', title: __('Refund detail'), icon: 'fa fa-list', classname: 'btn btn-xs btn-info btn-dialog btn-detail', url: $.fn.bootstrapTable.defaults.extend.detail_url});
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