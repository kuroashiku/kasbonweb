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
    <div class="easyui-layout" data-options="fit:true" id="po-layout">
        <div data-options="region:'north',split:false,border:false"
            style="height:200px;background-color:#8ae0ed;padding:5px">
            <table width="100%" height="0%">
                <tr>
                    <td width="100%" style="vertical-align:top">
                        <table width="100%" height="0%" style="font-size:14px">
                            <tr height="0%">
                                <input id="poa-form-id">
                            </tr>
                            <tr height="0%">
                                <input id="poa-form-supplier-id">
                            </tr>
                            <tr height="0%">
                                <td style="white-space:nowrap;padding-bottom:5px;vertical-align:middle"
                                    id="poa-form-nomor-label" width="19%">Nomor</td>
                                <td style="white-space:nowrap" width="81%"><input id="poa-form-nomor"></td>
                            </tr>
                            <tr height="0%">
                                <td style="white-space:nowrap;padding-bottom:5px;vertical-align:middle"
                                    id="poa-form-item-label" width="19%">Item</td>
                                <td style="white-space:nowrap" width="81%"><input id="poa-form-item"></td>
                            </tr>
                            <tr height="0%">
                                <td style="white-space:nowrap;padding-bottom:5px;vertical-align:middle"
                                    id="poa-form-supplier-label" width="19%">Supplier</td>
                                <td style="white-space:nowrap" width="81%"><input id="poa-form-supplier"></td>
                            </tr>
                            <tr height="0%">
                                <td style="white-space:nowrap;padding-bottom:5px;vertical-align:middle"
                                    id="poa-form-catatan-label" width="19%">Catatan</td>
                                <td style="white-space:nowrap" width="81%"><input id="poa-form-catatan"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <div data-options="region:'center',border:false">
            <div class="easyui-layout" data-options="fit:true">
                <div data-options="region:'center',border:false">
                    <div id="poa-grid"></div>
                    <div id="poa-grid-tb" style="padding:5px">
                        <div id="poa-btn-save"></div>
                        <div id="poa-btn-approve"></div>
                        <div id="poa-btn-close"></div>
                    </div>
                </div>
                <div data-options="region:'south',split:true" style="height:38px">
                    <div id="poa-total"></div>
                </div>
            </div>
        </div>
        <div data-options="region:'south',split:true" style="height:1px">
            <div id="notifpoa-grid"></div>
            <div id="notifpoa-grid-tb" style="padding:5px">
                <div id="notifpoa-label"></div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            var globalrowpoa=[];
            var globalnotifpoa=[];
            var globalhargapoa=0;
            var ul = $('#po-add-dlg').data('id');
            setTimeout(function(){
                if(ul==1&&ul){
                    var selectedRow = $('#po-grid').datagrid('getSelected');
                    globalrowpoa=selectedRow.poitems
                    $.each(globalrowpoa,function(i,v){
                        v.qty=parseInt(v.qty)
                        v.total=parseInt(v.poi_total);
                        v.itm_id=v.poi_itm_id;
                        v.satuan0hrg=parseInt(v.poi_satuan0hrg);
                        v.satuan0=v.poi_satuan0;
                        v.satuan0hpp=v.poi_satuan0hpp;
                        v.satuan1hrg=parseInt(v.poi_satuan1hrg);
                        v.satuan1=v.poi_satuan1;
                        v.satuan0of1=1;
                    })
                    console.log(selectedRow)
                    globalhargapoa=parseInt(selectedRow.po_total)
                    $('#poa-total').textbox('setValue',globalhargapoa);
                    $('#poa-form-nomor').textbox('setValue',selectedRow.po_nomor)
                    $('#poa-form-id').textbox('setValue',selectedRow.po_id)
                    $('#poa-grid').datagrid('loadData',globalrowpoa)
                }

            },100);
            
            $('#poa-form-id').textbox({
                width:0,
                readonly:true   
            });
            $('#poa-form-supplier-id').textbox({
                width:0,
                readonly:true   
            });
            $('#poa-form-nomor').textbox({
                width: 191,
                prompt:'Auto',
                readonly:true   
            });
            $('#poa-form-catatan').textbox({
                height:80,
                multiline:true,
            });
            $('#notifpoa-label').textbox({
                prompt:'Ditemukan multiitem',
                width:300, 
                readonly:true   
            });
            $('#notifpoa-label').textbox('textbox').css('background', 'rgb(245, 245, 245)');
            changeFieldBorderColor('#notifpoa-label', 'rgb(245, 245, 245)');
            $('#poa-form-id').next().hide();
            $('#poa-form-supplier-id').next().hide();
            $('#poa-total').textbox({
                width:300, 
                readonly:true   
            });
            $('#notifpoa-label').textbox({
                prompt:'Ditemukan multiitem',
                width:300, 
                readonly:true   
            });
            $('#poa-btn-close').linkbutton({
                text: 'Close',
                iconCls:'fa fa-times fa-lg',
                onClick:function() {
                    $('#po-add-dlg').dialog().dialog('close');
                    $('#po-add-dlg').dialog().dialog('destroy');
                }
            });
            $('#poa-btn-save').linkbutton({
                text: 'Save',
                iconCls:'fa fa-save fa-lg',
                onClick:function() {
                    poaSave();
                }
            });
            $('#poa-btn-approve').linkbutton({
                text: 'Approve',
                iconCls:'fa fa-check fa-lg',
                onClick:function() {
                    poaApprove();
                }
            });
            $('#poa-form-item').searchbox({
            searcher:function(value,name){
                if(value!='')
                {
                    $.post(getRestAPI("item/read"), {
                        lok_id:globalConfig.login_data.data.kas_lok_id,
                        key_val:value
                    },
                    function(data) {
                        var obj=JSON.parse(data)
                        if(obj.data.length>1)
                        {
                            $('#poa-form-supplier').textbox('disable');
                            $('#poa-form-item').textbox('disable');
                            $.each(obj.data,function(i,v){
                                v.qty=1;
                                v.total=v.itm_satuan1hrg;
                                v.satuan0hrg=v.itm_satuan1hrg;
                                v.satuan0=v.itm_satuan1;
                                v.satuan0hpp=v.itm_satuan1hpp;
                                v.satuan1hrg=v.itm_satuan1hrg;
                                v.satuan1=v.itm_satuan1;
                                v.satuan1hpp=v.itm_satuan1hpp;
                                v.satuan2hrg=v.itm_satuan2hrg;
                                v.satuan2=v.itm_satuan2;
                                v.satuan2hpp=v.itm_satuan2hpp;
                                v.satuan3hrg=v.itm_satuan3hrg;
                                v.satuan3=v.itm_satuan3;
                                v.satuan3hpp=v.itm_satuan3hpp;
                                v.satuan0of1=1;
                                v.id=v.itm_id;
                                v.nama=v.itm_nama;
                                v.tipe=1;
                                globalnotifpoa.push(v);
                            })
                            $('#po-layout').layout('panel','south').panel('resize',{
                                height: 380
                            });
                            $('#po-layout').layout('resize');
                            $('#notifpoa-grid').datagrid('loadData',globalnotifpoa);
                        }
                        else if(obj.data.length<1)
                        {
                            $.messager.alert("Error data kosong", "Maaf item tidak terdeteksi");
                        }
                        else{
                            obj.data[0].qty=1;
                            obj.data[0].total=obj.data[0].itm_satuan1hrg;
                            obj.data[0].satuan0hrg=obj.data[0].itm_satuan1hrg;
                            obj.data[0].satuan0=obj.data[0].itm_satuan1;
                            obj.data[0].satuan0hpp=obj.data[0].itm_satuan1hpp;
                            obj.data[0].satuan1hrg=obj.data[0].itm_satuan1hrg;
                            obj.data[0].satuan1=obj.data[0].itm_satuan1;
                            obj.data[0].satuan1hpp=obj.data[0].itm_satuan1hpp;
                            obj.data[0].satuan0of1=1;
                            $.each(globalrowpoa,function(i,v){
                                if(v.itm_id==obj.data[0].itm_id)
                                {
                                    itemflag=1;
                                }
                            })
                            if(itemflag==0){
                                globalhargapoa+=parseInt(obj.data[0].satuan1hrg)
                                globalrowpoa.push(obj.data[0]);
                                $('#poa-total').textbox('setValue',globalhargapoa);
                                $('#poa-grid').datagrid('loadData',globalrowpoa);
                                var lastIndex = $('#poa-grid').datagrid('getRows').length - 1;
                                $('#poa-grid').datagrid('selectRow',lastIndex);
                            }
                            else{
                                $.messager.alert("Error item kembar", "Item sudah terdaftar");  
                                itemflag=0;
                            }
                        }
                    })
                }
            },
            height: 'auto',
            prompt:'Masukkan Item'
        });
        $('#poa-form-supplier').searchbox({
            searcher:function(value,name){
                if(value!='')
                {
                    $.post(getRestAPI("supplier/search"), {
                        com_id:globalConfig.login_data.data.kas_com_id,
                        q:value
                    },
                    function(data) {
                        
                        var obj=JSON.parse(data)
                        console.log(obj)
                        if(obj.data.length>1)
                        {
                            $('#poa-form-supplier').textbox('disable');
                            $('#poa-form-item').textbox('disable');
                            $.each(obj.data,function(i,v){
                                v.id=v.sup_id;
                                v.nama=v.sup_nama;
                                v.tipe=2;
                                globalnotifpoa.push(v);
                            })
                            $('#po-layout').layout('panel','south').panel('resize',{
                                height: 380
                            });
                            $('#po-layout').layout('resize');
                            $('#notifpoa-grid').datagrid('loadData',globalnotifpoa);
                        }
                        else if(obj.data.length<1)
                        {
                            $.messager.alert("Error data kosong", "Maaf item tidak terdeteksi");
                        }
                        else{
                            $('#poa-form-supplier').searchbox('setValue',obj.data[0].sup_nama)
                            $('#poa-form-supplier-id').searchbox('setValue',obj.data[0].sup_id)
                        }
                    })
                }
            },
            height: 'auto',
            prompt:'Masukkan Supplier'
        });
        $('#poa-grid').datagrid({
            border:false,
            toolbar:'#poa-grid-tb',
            fit:true,
            singleSelect:true,
            columns:[[{
                field:'itm_id',
                title:'ID',
                resizable:false,
                width:35,
                hidden:true
            },{
                field:'itm_nama',
                title:'Detail Barang',
                resizable:false,
                width:300,
                formatter: function(value, row) { 
                    console.log(row);
                    console.log(globalrowpoa);
                    return '<div class="row" style="padding-bottom:3px;">'+
                            '<div class="col-md-12" style="font-weight: bold;">'+
                                row.itm_nama+
                            '</div>'+
                        '</div>'+
                        '<div class="row" style="padding-bottom:3px;">'+
                            '<div class="col-md-12">'+
                                row.qty+' '+row.satuan0+' @ Rp.'+currencyFormat(row.satuan0hrg)+',00'
                            '</div>'+
                        '</div>'+                        
                    '</div>'
                }
            },{
                field:'total',
                title:'Total',
                resizable:false,
                width:150,
                align:"right",
                formatter: function(value, row) {return 'Rp.'+currencyFormat(row.total)+',00'}
            }]],
            data:globalrowpoa,
            onSelect:function(index,row){
                console.log(row)
                
            }
        });
        $('#notifpoa-grid').datagrid({
            border:false,
            toolbar:'#notifpoa-grid-tb',
            fit:true,
            singleSelect:true,
            columns:[[{
                field:'id',
                title:'ID',
                resizable:false,
                width:35,
                hidden:true
            },
            {
                field:'nama',
                title:'Nama',
                resizable:false,
                width:350,
            },
            {
                field:'tipe',
                title:'Tipe',
                resizable:false,
                width:35,
                hidden:true
            }]],
            data:globalnotifpoa,
            onSelect:function(index,row){
                console.log(row)
                $('#poa-form-supplier').textbox('enable');
                $('#poa-form-item').textbox('enable');
                if(row.tipe==1)
                {
                    var itemflagnotif=0;
                    $.each(globalrow,function(i,v){
                        if(v.itm_id==row.itm_id)
                        {
                            itemflagnotif=1;
                        }
                    })
                    if(itemflagnotif==0){
                        if(parseInt(row.itm_stok)>0)
                        {
                            globalrowpoa.push(row)
                            globalhargapoa=globalhargapoa+parseInt(row.satuan1hrg);
                            $('#poa-grid').datagrid('loadData',globalrowpoa);
                            globalnotifpoa=[];
                            $('#notifpoa-grid').datagrid('loadData',globalnotifpoa);
                            $('#poa-form-item').textbox('setValue','')
                            $('#poa-total').textbox('setValue',globalhargapoa);
                        }
                        else {
                            $('#searchitem').searchbox('setValue','');
                            globalnotifpoa=[];
                            $('#notifpoagrid').datagrid('loadData',globalnotifpoa);
                            $.messager.alert("Error item kosong", "Item habis");  
                        }
                    }
                    else{
                        globalnotifpoa=[];
                        $('#notifpoagrid').datagrid('loadData',globalnotifpoa);
                        $.messager.alert("Error item kembar", "Item sudah terdaftar");  
                        itemflag=0;
                    }
                    
                }
                else if(row.tipe==2){
                    $('#poa-form-item').textbox('setValue','')
                    $('#poa-form-supplier').textbox('setValue',row.sup_nama)
                    $('#poa-form-supplier-id').textbox('setValue',row.sup_id)
                    globalnotifpoa=[];
                    $('#notifpoagrid').datagrid('loadData',globalnotifpoa);
                }
                // $('#pos-layout').layout('collapse','east');
                $('#po-layout').layout('panel','south').panel('resize',{
                    height: 1
                });
                $('#po-layout').layout('resize');
            } 
        })
        function poaSave(){
            $.ajax({
                type:'POST',
                data:{rows:globalrowpoa,
                    po_total:globalhargapoa,
                    po_kas_id:globalConfig.login_data.data.kas_id,
                    po_lok_id:globalConfig.login_data.data.kas_lok_id,
                    po_sup_id:parseInt($('#poa-form-supplier-id').textbox('getValue')),
                    po_status:'OPEN',
                    po_catatan:$('#poa-form-catatan').textbox('getValue'),
                },
                url:getRestAPI('po/save'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    console.log(retval)
                    $('#poa-form-id').textbox('setValue',obj.po_id);
                }
            })
        }
        function poaApprove(){
            $.ajax({
                type:'POST',
                data:{
                    po_id:$('#poa-form-id').textbox('getValue'),
                    
                },
                url:getRestAPI('po/approve'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                }
            })
        }
        function poaClearKustomer(){
            indexpoa=-1;
            $('#poa-form-nama').textbox('setValue','');
            $('#poa-form-stok').numberbox('setValue','');
            $('#poa-form-harga').numberbox('setValue','');
            $('#poa-form-kode').textbox('setValue','');
            $('#poa-form-satuan').textbox('setValue','');
            $('#poa-form-gambar').textbox('setValue','');
            $('#poa-form-gambar-edit').textbox('setValue','');
            $('#poa-form-harga-awal').numberbox('setValue','');
            $('#poa-grid').datagrid('unselectRow', indexpoa);
        }
        ///////fungsi penunjang
        function changeFieldBorderColor(field, color){
            var t = $(field);
            var el = t.data('textbox') ? t.next() : $(t);
            el.css('border-color', color);
        }
    })
    
    </script>
    </body>
</html>