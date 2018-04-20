define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'auth/admin/index',
                    add_url: 'auth/admin/add',
                    edit_url: 'auth/admin/edit',
                    del_url: 'auth/admin/del',
                    multi_url: 'auth/admin/multi',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                columns: [
                    [
                        {field: 'state', checkbox: true, },
                        {field: 'id', title: 'ID'},
                        {field: 'username', title: __('Username')},
                        {field: 'nickname', title: __('Nickname')},
                        {field: 'groups_text', title: __('Group'), operate: false, formatter: Table.api.formatter.label},
                        {field: 'email', title: __('Email')},
                        {field: 'status', title: __("Status"), formatter: Table.api.formatter.status},
                        {field: 'logintime', title: __('Login time'), formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: function (value, row, index) {
                                if (row.id == Config.admin.id) {
                                    return '';
                                }
                                return Table.api.formatter.operate.call(this, value, row, index);
                            }}
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
                $(document).on("change", "#admin_group", function (e) {
                    $(".sp_hidden").attr('novalidate', true).parent().parent().parent().addClass("hidden");
                    $.each($("#admin_group").val(), function (k, v) {
                        switch (v) {
                            case '6':
                                $("#c-partner_id").attr("name", 'extend[' + v + ']').removeAttr('novalidate').parent().parent().parent().removeClass("hidden");
                                break;
                            case '7':
                                $("#c-company_id").attr("name", 'extend[' + v + ']').removeAttr('novalidate').parent().parent().parent().removeClass("hidden");
                                break;
                            case '8':
                                $("#c-village_id").attr("name", 'extend[' + v + ']').removeAttr('novalidate').parent().parent().parent().removeClass("hidden");
                                break;
                            default:
                        }
                    });
                });
            }
        }
    };
    return Controller;
});