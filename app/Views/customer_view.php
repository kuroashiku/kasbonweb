<html>
    <body>
        
    <script type="text/javascript">
        $.parser.onComplete = function(){
            $('body').css('visibility','visible');
        };
    </script>
    <div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'north',split:false,border:false"
            style="height:80px;background-color:#8ae0ed;padding:5px">
            <table width="100%" height="0%">
                <tr>
                    <td width="0%" style="vertical-align:top">
                        <table width="70%" height="0%" style="font-size:14px">
                            <tr height="0%">
                                <input id="cua-form-id">
                            </tr>
                            <tr height="0%">
                                <td style="white-space:nowrap;vertical-align:middle"
                                    id="cua-form-kustomer-label" width="0%">Nama Kustomer</td>
                                <td style="white-space:nowrap" width="100%"><input id="cua-form-kustomer"></td>
                            </tr>
                            <tr height="0%">
                                <td style="white-space:nowrap;vertical-align:middle"
                                    id="cua-form-nowa-label" width="0%">No WA</td>
                                <td style="white-space:nowrap" width="100%"><input id="cua-form-nowa"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <div data-options="region:'center',border:false">
            <div id="cua-grid"></div>
            <div id="cua-grid-tb" style="padding:5px">
                <div id="cua-btn-kustomerclear"></div>
                <div id="cua-btn-kustomersave"></div>
                <div id="cua-kustomersearch"></div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
        var indexcua=-1;
        $('#cua-form-id').textbox({
            width:80,
            readonly:true,
        });
        $('#cua-form-id').textbox('textbox').parent().hide();
        $('#cua-form-kustomer').textbox({
            prompt:'Isi nama kustomer',
            width:300, 
        });
        $('#cua-form-nowa').textbox({
            prompt:'Isi no wa',
            width:300, 
        });
        $('#cua-btn-kustomerclear').linkbutton({
            text: 'Bersihkan',
            iconCls:'fa fa-brush fa-lg',
            onClick:function() {cuaClearKustomer();}
        });
        $('#cua-btn-kustomersave').linkbutton({
            text: 'Simpan',
            iconCls:'fa fa-user-plus fa-lg',
            onClick:function() {cuaSaveKustomer();}
        });
        $.post(getRestAPI("customer/read"), {
            com_id:globalConfig.login_data.data.kas_com_id
        },
        function(data) {
            var obj = JSON.parse(data);
            $('#cua-grid').datagrid({
                border:false,
                singleSelect:true,
                editorHeight:22,
                toolbar:'#cua-grid-tb',
                fit:true,
                columns:[[{
                    field:'cus_id',
                    title:'ID',
                    resizable:false,
                    width:40,
                    hidden:true
                },{
                    field:'cus_nama',
                    title:'Nama',
                    resizeble:false,
                    width:200
                },{
                    field:'cus_wa',
                    title:'No Wa',
                    resizeble:false,
                    width:200
                }]],
                data:obj.data,
                onSelect:function(index, row) {
                    indexcua=index;
                    $('#cua-form-kustomer').textbox('setValue',row.cus_nama);
                    $('#cua-form-nowa').textbox('setValue',row.cus_wa);
                    $('#cua-form-id').textbox('setValue',row.cus_id);
                }
            });
        });
        function cuaClearKustomer(){
            indexcua=-1;
            $('#cua-form-kustomer').textbox('setValue','');
            $('#cua-form-nowa').textbox('setValue','');
            $('#cua-grid').datagrid('unselectRow', indexcua);
        }
        function cuaSaveKustomer(){
            $.ajax({
                type:'POST',
                data:{
                    cus_nama:$('#cua-form-kustomer').textbox('getValue'),
                    cus_wa:$('#cua-form-nowa').textbox('getValue'),
                    cus_com_id:globalConfig.login_data.data.kas_com_id,
                    cus_id:indexcua==-1?-1:$('#cua-form-id').textbox('getValue')
                },
                url:getRestAPI('customer/save'),
                success:function(retval) {
                    indexcua=-1;
                
                    $('#cua-form-kustomer').textbox('setValue','');
                    $('#cua-form-nowa').textbox('setValue','');
                    $.post(getRestAPI("customer/read"), {
                        com_id:globalConfig.login_data.data.kas_com_id
                    },
                    function(data) {
                    var obj = JSON.parse(data);
                    $('#cua-grid').datagrid('loadData',obj.data)
                    });
                }
            })
        }
    })
    
    </script>
    </body>
</html>