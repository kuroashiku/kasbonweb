<html>
    <body>
    <style>
        div.datagrid-header{
            display:block !imrcvrtant;
        }

    </style>
    <script type="text/javascript">
        $.parser.onComplete = function(){
            $('body').css('visibility','visible');
        };
    </script>
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',border:false">
            <div id="rcv-grid"></div>
            <div id="rcv-grid-tb" style="padding:5px">
                <table width="100%">
                    <tr>
                        <td width="0%" style="padding-right:5px;white-space:nowrap">
                            <input id="rcv-form-search">
                            <div id="rcv-btn-add"></div>
                        </td>
                    </tr>
                    
                </table>
            </div>
            
        </div>
        <div id="rcv-add-dlg" style="top:0px"></div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#rcv-form-search').searchbox({
            searcher:function(value,name){
                if(value!='')
                {
                    $.post(getRestAPI("rcv/read"), {
                        lok_id:globalConfig.login_data.data.kas_lok_id,
                        q:value,
                        loaditems:'yes',
                        orifields:'yes',
                    },
                    function(data) {
                        var obj=JSON.parse(data)
                        $('#rcv-grid').datagrid('loadData',obj.data)
                    })
                }
            },
            height: 'auto',
            width:300
        });
        $('#rcv-btn-add').linkbutton({
            iconCls:'fa fa-plus-circle fa-lg',
            onClick:function() {addRcv(0);
            $('#rcv-rcv-dlg').dialog().dialog('close');
            
            }
        });
        $.post(getRestAPI("receive/read"), {
            lok_id:globalConfig.login_data.data.kas_lok_id,
            loaditems:'yes',
        },
        function(data) {
            var obj = JSON.parse(data);
            $('#rcv-grid').datagrid({
                border:false,
                singleSelect:true,
                editorHeight:22,
                toolbar:'#rcv-grid-tb',
                fit:true,
                columns:[[{
                    field:'id',
                    title:'ID',
                    resizable:false,
                    width:40,
                    hidden:true
                },{
                    field:'nomor',
                    title:'Detail Barang',
                    resizeble:false,
                    width:550,
                    formatter: function(value, row) { 
                        var status='';
                        if((row.status.localeCompare('PAID'))==0){
                            status='Lunas';
                        }
                        var items='';
                        $.each(row.rcvitems,function(i,v){
                            items=items+
                            '<div class="row" style="padding-bottom:3px;">'+
                                '<div class="col-md-9">'+
                                    v.itm_nama+
                                '</div>'+
                                
                            '</div>'+
                            '<div class="row" style="padding-bottom:3px;">'+
                                '<div class="col-md-12" style="font-size:16px">'+
                                    v.qty+" "+(v.satuan?v.satuan:v.satuan1)+
                                '</div>'+
                            '</div>'
                        })
                        return '<div class="row" style="padding-bottom:3px;">'+
                                '<div class="col-md-4" style="font-weight: bold;color: blue;">'+
                                    row.nomor+
                                '</div>'+
                                '<div class="col-md-8" style="color:#ff0000">'+
                                    status+
                                '</div>'+
                            '</div>'+
                            '<div class="row" style="padding-bottom:3px;">'+
                                '<div class="col-md-12" style="color: gray;">'+
                                    'PO: '+row.po_nomor+
                                '</div>'+
                            '</div>'+
                            items+              
                        '</div>'
                    }
                },{
                    field:'total',
                    title:'Jumlah',
                    resizeble:false,
                    width:200,
                    formatter: function(value, row) { 
                        console.log(row)
                        var items='';
                        $.each(row.rcvitems,function(i,v){
                            items=items+
                            '<div class="row" style="padding-bottom:3px;">'+
                                '<div class="col-md-12">'+
                                    v.total+
                                '</div>'+
                            '</div>'+
                            '<div class="row" style="padding-bottom:3px;">'+
                                '<div class="col-md-12" style="font-size:16px; color:transparent">'+
                                    v.satuan+
                                '</div>'+
                            '</div>'
                        })
                        return '<div class="row" style="padding-bottom:3px;">'+
                                '<div class="col-md-12" style="font-weight: bold;">'+
                                    row.total+
                                '</div>'+
                            '</div>'+
                            '<div class="row" style="padding-bottom:3px;">'+
                                '<div class="col-md-12" style="font-weight: bold;">'+
                                    '<br>'+
                                '</div>'+
                            '</div>'+
                            items+
                                                    
                        '</div>'
                    }
                }]],
                data:obj.data,
                onDblClickRow:function(index,row){
                    $.each(obj.data,function(i,v){
                        v.edit=1
                    })
                    addRcv(1)
                }
            });
            
        });
        function addRcv(id){
            $('#rcv-add-dlg').dialog().dialog('close');
            $('#rcv-add-dlg').dialog({
                title:'Tambah Receive',
                width:470,
                height:600,
                closable:false,
                border:true,
                modal:true,
                resizable:false,
                maximizable:false,
                href:'pos/receive_add',
            });
            $('#rcv-add-dlg').data('id', id ).dialog('open');
        }
    })
    
    </script>
    </body>
</html>