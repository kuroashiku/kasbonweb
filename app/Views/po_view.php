<html>
    <body>
    <style>
        div.datagrid-header{
            display:block !important;
        }
        
    </style>
    <script type="text/javascript">
        $.parser.onComplete = function(){
            $('body').css('visibility','visible');
        };
    </script>
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',border:false">
            <div id="po-grid"></div>
            <div id="po-grid-tb" style="padding:5px">
                <table width="100%">
                    <tr>
                        <td width="0%" style="padding-right:5px;white-space:nowrap">
                            <input id="po-form-search">
                            <div id="po-btn-add"></div>
                        </td>
                    </tr>
                    
                </table>
            </div>
            
        </div>
        <div id="po-add-dlg" style="top:0px"></div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#po-form-search').searchbox({
            searcher:function(value,name){
                if(value!='')
                {
                    $.post(getRestAPI("po/read"), {
                        lok_id:globalConfig.login_data.data.kas_lok_id,
                        q:value,
                        loaditems:'yes',
                        orifields:'yes',
                    },
                    function(data) {
                        var obj=JSON.parse(data)
                        $('#po-grid').datagrid('loadData',obj.data)
                    })
                }
            },
            height: 'auto',
            width:300
        });
        $('#po-btn-add').linkbutton({
            iconCls:'fa fa-plus-circle fa-lg',
            onClick:function() {addPO(0);
            $('#pos-po-dlg').dialog().dialog('close');
            
            }
        });
        $.post(getRestAPI("po/read"), {
            lok_id:globalConfig.login_data.data.kas_lok_id,
            loaditems:'yes',
            orifields:'yes',
        },
        function(data) {
            var obj = JSON.parse(data);
            $('#po-grid').datagrid({
                border:false,
                singleSelect:true,
                editorHeight:22,
                toolbar:'#po-grid-tb',
                fit:true,
                columns:[[{
                    field:'po_id',
                    title:'ID',
                    resizable:false,
                    width:40,
                    hidden:true
                },{
                    field:'po_nomor',
                    title:'Detail Barang',
                    resizeble:false,
                    width:550,
                    formatter: function(value, row) { 
                        var status='';
                        if((row.po_status.localeCompare('APPROVED'))==0){
                            status='Disetujui';
                        }
                        else if((row.po_status.localeCompare('COMPLETED'))==0){
                            status='Selesai';
                        }
                        var items='';
                        $.each(row.poitems,function(i,v){
                            items=items+
                            '<div class="row" style="padding-bottom:3px;">'+
                                '<div class="col-md-9">'+
                                    v.itm_nama+
                                '</div>'+
                                
                            '</div>'+
                            '<div class="row" style="padding-bottom:3px;">'+
                                '<div class="col-md-12" style="font-size:16px">'+
                                    v.qty+" "+(v.poi_satuan0?v.poi_satuan0:v.poi_satuan1)+
                                '</div>'+
                            '</div>'
                        })
                        return '<div class="row" style="padding-bottom:3px;">'+
                                '<div class="col-md-4" style="font-weight: bold;">'+
                                    row.po_nomor+
                                '</div>'+
                                '<div class="col-md-8" style="color:#ff0000">'+
                                    status+
                                '</div>'+
                            '</div>'+
                            items+
                                                    
                        '</div>'
                    }
                },{
                    field:'po_total',
                    title:'Jumlah',
                    resizeble:false,
                    width:200,
                    formatter: function(value, row) { 
                        var items='';
                        $.each(row.poitems,function(i,v){
                            items=items+
                            '<div class="row" style="padding-bottom:3px;">'+
                                '<div class="col-md-12">'+
                                    v.poi_total+
                                '</div>'+
                            '</div>'+
                            '<div class="row" style="padding-bottom:3px;">'+
                                '<div class="col-md-12" style="font-size:16px; color:transparent">'+
                                    v.poi_satuan0+
                                '</div>'+
                            '</div>'
                        })
                        return '<div class="row" style="padding-bottom:3px;">'+
                                '<div class="col-md-12" style="font-weight: bold;">'+
                                    row.po_total+
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
                    addPO(1)
                }
            });
            
        });
        function addPO(id){
            $('#po-add-dlg').dialog().dialog('close');
            $('#po-add-dlg').dialog({
                title:'Tambah PO',
                width:470,
                height:600,
                closable:false,
                border:true,
                modal:true,
                resizable:false,
                maximizable:false,
                href:'pos/po_add',
            });
            $('#po-add-dlg').data('id', id ).dialog('open');
        }
    })
    
    </script>
    </body>
</html>