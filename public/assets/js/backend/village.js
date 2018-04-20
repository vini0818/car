define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'template'], function ($, undefined, Backend, Table, Form, Template) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'village/index',
                    add_url: 'village/add',
                    edit_url: 'village/edit',
                    del_url: 'village/del',
                    multi_url: 'village/multi',
                    table: 'village',
                }
            });

            var table = $("#table");

            //在普通搜索提交搜索前
            table.on('common-search.bs.table', function (event, table, params, query) {
                //这里可以对params值进行修改,从而影响搜索条件
                params.filter = JSON.parse(params.filter);
                params.op = JSON.parse(params.op);
                var province_id = $("[name='row[province_id]']", table.$commonsearch).val();
                var city_id = $("[name='row[city_id]']", table.$commonsearch).val();
                var area_id = $("[name='row[area_id]']", table.$commonsearch).val();
                if (province_id) {
                    params.filter['province_id'] = $.trim(province_id);
                    params.op['province_id'] = "=";
                } else {
                    delete params.filter['province_id'];
                    delete params.op['province_id'];
                }
                if (city_id) {
                    params.filter['city_id'] = $.trim(city_id);
                    params.op['city_id'] = "=";
                } else {
                    delete params.filter['city_id'];
                    delete params.op['city_id'];
                }
                if (area_id) {
                    params.filter['area_id'] = $.trim(area_id);
                    params.op['area_id'] = '=';
                } else {
                    delete params.filter['area_id'];
                    delete params.op['area_id'];
                }
                params.filter = JSON.stringify(params.filter);
                params.op = JSON.stringify(params.op);
                return params;
            });
            //在普通搜索渲染后
            table.on('post-common-search.bs.table', function (event, table) {
                //合伙人搜索下拉内容（从数据表中读取）
                $("input[name='partner_id']", table.$commonsearch).addClass("selectpage").data("source", "user/partner/selectpage").data("primaryKey", "id").data("field", "name").data("orderBy", "id desc");
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
                        {field: 'name', title: __('Name'), formatter: Controller.api.formatter.name, operate: 'LIKE %...%'},
                        //省市区联动搜索 通过id指定联动搜索的所用到的div
                        {field: 'row', title: __('Province_id') + '/' + __('City_id') + '/' + __('Area_id'), formatter: Controller.api.formatter.xzqh, operate: '=', searchList: function () {
                                return Template('village_xzqh', {});
                            }
                        },
                        {field: 'address', title: __('Address'), operate: false},
                        {field: 'contact', title: __('Contact')},
                        {field: 'tel', title: __('Tel')},
                        {field: 'partner_id', title: __('Partner_name'), formatter: Controller.api.formatter.partner_id},
                        {field: 'company_id', title: __('Company_name'), formatter: Controller.api.formatter.company_id},
                        {field: 'type', title: __('Type'), searchList: $.getJSON('ajax/dict?name=village_type'), formatter: Controller.api.formatter.type},
                        {field: 'status', title: __('Status'), searchList: $.getJSON('ajax/dict?name=status&status=1'), formatter: Table.api.formatter.status},
                        {field: 'status', title: __('Enable/Disable'), formatter: Controller.api.formatter.status, operate: false},
                        {field: 'enable_time', title: __('Enable_time'), operate: false},
                        {field: 'disable_time', title: __('Disable_time'), operate: false},
                        {field: 'create_time', title: __('Create_time'), operate: false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ],
                //可以控制是否默认显示搜索单表,false则隐藏,默认为false
                searchFormVisible: true
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
                    var url = "car/carport?village_id=" + row.id;
                    //方式一,直接返回class带有addtabsit的链接,这可以方便自定义显示内容
                    return '<a href="' + url + '" class="label label-success addtabsit" title="' + __("Search %s", value) + '">' + __(value) + '</a>';
                    //方式二,直接调用Table.api.formatter.addtabs
//                    return Table.api.formatter.addtabs(value, row, index, url);
                },
                xzqh: function (value, row, index) {
                    return row.province_name + '-' + row.city_name + '-' + row.area_name;
                },
                partner_id: function (value, row, index) {
                    return row.partner_name;
                },
                company_id: function (value, row, index) {
                    return row.company_name;
                },
                type: function (value, row, index) {
                    return row.type_name;
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