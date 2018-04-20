define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'user/user/index',
                    detail_url: 'user/user/detail',
                    edit_url: 'user/user/edit',
                    multi_url: 'user/user/multi',
                    table: 'user',
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
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate: false},
                        {field: 'openid', title: __('Openid'), operate: false},
                        {field: 'phone', title: __('Phone')},
                        {field: 'nickname', title: __('Nickname')},
                        {field: 'sex_name', title: __('Sex'), operate: false},
                        {field: 'icon', title: __('Icon'), formatter: Table.api.formatter.image, operate: false},
                        {field: 'country', title: __('Country'), operate: false},
                        {field: 'province', title: __('Province'), operate: false},
                        {field: 'city', title: __('City'), operate: false},
                        {field: 'balance', title: __('Balance'), operate: false},
                        {field: 'is_agree', title: __('Is_agree'), formatter: Controller.api.formatter.is_agree, operate: false},
                        {field: 'deposit_status', title: __('Deposit_status'), searchList: $.getJSON('ajax/dict?name=deposit_status')},
                        {field: 'member_card', title: __('Member_card')},
                        {field: 'member_card_rfid', title: __('Member_card_rfid'), operate: false},
                        {field: 'member_card_status', title: __('Member_card_status'), searchList: $.getJSON('ajax/dict?name=mem_card'), formatter: Table.api.formatter.status, custom: {unactive: 'primary', apply: 'info', sending: 'info', active: 'success'}},
                        {field: 'create_time', title: __('Create_time'), operate: 'RANGE', addclass: 'datetimerange'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [{
                                    name: 'detail',
                                    text: __('Detail'),
                                    icon: 'fa fa-list',
                                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                                    url: $.fn.bootstrapTable.defaults.extend.detail_url
                                }],
                            formatter: Table.api.formatter.operate
                        }
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
                is_agree: function (value, row, index) {
                    return value === '1' ? __('Yes') : __('No');
                }
            }
        }
    };
    return Controller;
});