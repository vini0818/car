define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'user/usercard/index',
                    audit_url: 'user/usercard/audit',
                    send_url: 'user/usercard/edit',
                    table: 'user_card'
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
                        {field: 'id', title: __('Id')},
                        {field: 'user_id', title: __('User_name'), formatter: Controller.api.formatter.user_id},
                        {field: 'name', title: __('Name')},
                        {field: 'phone', title: __('Phone')},
                        {field: 'address', title: __('Address')},
                        {field: 'status', title: __('Status'), formatter: Controller.api.formatter.status},
                        {field: 'create_time', title: __('Create_time')},
                        {field: 'update_time', title: __('Update_time')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, //Table.api.events.operate：有edit_url,delete_url时，默认添加“修改”、“删除”操作事件
                            buttons: [],
                            formatter: Controller.api.formatter.buttons
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        audit: function () {
            //审核操作绑定事件
            Controller.api.bindevent();
        },
        edit: function () {
            //修改操作绑定事件
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
                $("input:radio[name='row[status]']").change(function () {
                    if ($(this).val() === '1') {
                        $("#c-member_card_rfid").removeAttr('novalidate').parent().parent().removeClass("hidden");
                    } else {
                        $("#c-member_card_rfid").attr('novalidate', true).parent().parent().addClass("hidden");
                    }
                });
            },
            formatter: {
                user_id: function (value, row, index) {
                    return row.user_name;
                },
                status: function (value, row, index) {
                    //颜色状态数组,可使用red/yellow/aqua/blue/navy/teal/olive/lime/fuchsia/purple/maroon
                    var colorArr = {wait_audit: 'info', passed: 'success', rejected: 'danger', deleted: 'grey'};
                    value = value.toString();
                    var color = value && typeof colorArr[value] !== 'undefined' ? colorArr[value] : 'primary';
                    value = value.charAt(0).toUpperCase() + value.slice(1);
                    //渲染状态
                    var html = '<span class="text-' + color + '"><i class="fa fa-circle"></i> ' + __(value) + '</span>';
                    return html;
                },
                buttons: function (value, row, index) {
                    var buttons = $.extend([], this.buttons || []);
                    if (row.status === 'wait_audit') {
                        buttons.push({
                            name: 'audit', //
                            text: __('Audit'), //按钮显示标题
                            icon: 'fa fa-list', //按钮显示图标
                            classname: 'btn btn-success btn-xs btn-audit btn-dialog', //按钮显示样式
                            url: $.fn.bootstrapTable.defaults.extend.audit_url //按钮请求地址
                        });
                    } else if (row.status === 'passed' && row.member_card_status === 1) {
                        buttons.push({
                            name: 'edit', //
                            text: __('Send'), //按钮显示标题
                            icon: 'fa fa-list', //按钮显示图标
                            classname: 'btn btn-danger btn-xs btn-edit btn-dialog', //按钮显示样式
                            url: $.fn.bootstrapTable.defaults.extend.send_url //按钮请求地址
                        });
                    }
                    return Table.api.buttonlink(this, buttons, value, row, index, 'buttons');
                }
            }
        }
    };
    return Controller;
});

function closeLayer() {
    var index = parent.Layer.getFrameIndex(window.name);
    parent.Layer.close(index);
}