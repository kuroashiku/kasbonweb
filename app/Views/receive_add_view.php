<html>
    <body>
    <style>
        div.datagrid-header{
            display:block !important;
        }
        /* a.combo-arrow{
          padding-left:0px;
          width:20px !important;
          background: #fff;
        }
        span.combo{
            width:200px !important;
        } */
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
                                <input id="rca-form-id">
                            </tr>
                            <tr height="0%">
                                <input id="rca-form-po-id">
                            </tr>
                            <tr height="0%">
                                <input id="rca-form-supplier-id">
                            </tr>
                            <tr height="0%">
                                <td style="white-space:nowrap;padding-bottom:5px;vertical-align:middle"
                                    id="rca-form-nomor-label" width="19%">Nomor</td>
                                <td style="white-space:nowrap" width="81%"><input id="rca-form-nomor"></td>
                            </tr>
                            <tr height="0%">
                                <td style="white-space:nowrap;padding-bottom:5px;vertical-align:middle"
                                    id="rca-form-po-label" width="19%">PO</td>
                                <td style="white-space:nowrap" width="81%"><input id="rca-form-po"><input id="rca-form-po-nomor"></td>
                            </tr>
                            <tr height="0%">
                                <td style="white-space:nowrap;padding-bottom:5px;vertical-align:middle"
                                    id="rca-form-catatan-label" width="19%">Catatan</td>
                                <td style="white-space:nowrap" width="81%"><input id="rca-form-catatan"></td>
                            </tr>
                            <tr>
                                <td style="white-space:nowrap;">Qty/Diskon</td>
                                <td style="white-space:nowrap;">
                                <div id="rca-btn-leftqty" title="Kurangkan kuantiti"></div>
                                <div id="rca-qty"></div>
                                <div id="rca-btn-rightqty" title="Tambahkan kuantiti"></div>
                                <div id="rca-btn-leftdsc" title="Kurangkan diskon"></div>
                                <div id="rca-dsc"></div>
                                <div id="rca-btn-rightdsc" title="Tambahkan diskon"></div>
                                </td>
                            </tr>
                            <tr>
                                <td style="white-space:nowrap;">Diskon Global</td>
                                <td style="white-space:nowrap;">
                                <div id="rca-btn-leftdscg" title="Kurangkan diskon"></div>
                                <div id="rca-dscg"></div>
                                <div id="rca-btn-rightdscg" title="Tambahkan diskon"></div>
                                </td>
                            </tr>
                            <tr>
                                <td style="white-space:nowrap;">
                                </td>
                                <td style="white-space:nowrap;">
                                <div id="rca-total"></div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <div data-options="region:'center',border:false">
            <div class="easyui-layout" data-options="fit:true">
                <div data-options="region:'center',border:false">
                    <div id="rca-grid"></div>
                    <div id="rca-grid-tb" style="padding:5px">
                        <div id="rca-btn-save"></div>
                        <div id="rca-btn-lunas"></div>
                        <div id="rca-btn-close"></div>
                    </div>
                </div>
                <div data-options="region:'south',split:true" style="height:38px">
                    <div id="rca-total"></div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
        var globalrowrca=[];
        var globalnotifrca=[];
        var globalhargarca=0;
        var globalidrca=0;
        var globaldiskon=0;
        var ul = $('#rcv-add-dlg').data('id');
        setTimeout(function(){
            if(ul==1&&ul){
                var selectedRow = $('#rcv-grid').datagrid('getSelected');
                globalrowrca=selectedRow.rcvitems
                console.log(selectedRow.rcvitems)
                $.each(globalrowrca,function(i,v){
                    console.log(parseInt(v.satuan1hpp))
                    parseInt(v.satuan3hrg)!=0?(v.satuan0hrg=v.satuan3hrg):(v.satuan2hrg!=0?(v.satuan0hrg=v.satuan2hrg):v.satuan0hrg=v.satuan1hrg)
                    parseInt(v.satuan3hpp)!=0?(v.satuan0hpp=v.satuan3hpp):(v.satuan2hpp!=0?(v.satuan0hpp=v.satuan2hpp):v.satuan0hpp=v.satuan1hpp)
                    parseInt(v.satuan3of1)!=0?(v.satuan0of1=v.satuan3of1):(v.satuan2of1!=0?(v.satuan0of1=v.satuan2of1):v.satuan0of1=1)
                    v.poi_satuan0=v.satuan0
                    v.poi_satuan0hpp=v.satuan0hpp
                    v.poi_satuan0hrg=v.satuan0hrg
                })
                // console.log(selectedRow)
                globalhargarca=parseInt(selectedRow.po_total)
                $('#rca-total').textbox('setValue',globalhargarca);
                $('#rca-form-nomor').textbox('setValue',selectedRow.nomor)
                $('#rca-form-id').textbox('setValue',selectedRow.rcv_id)
                $('#rca-form-po-id').textbox('setValue',selectedRow.po_id)
                $('#rca-form-po-nomor').textbox('setValue',selectedRow.po_nomor)
                $('#rca-form-po-nomor').next().show();
                $('#rca-form-po').next().hide();
                $('#rca-grid').datagrid('loadData',globalrowrca)
            }

        },100);
        
        $('#rca-form-id').textbox({
            width:0,
            readonly:true   
        });
        $('#rca-form-po-id').textbox({
            width:0,
            readonly:true   
        });
        $('#rca-form-supplier-id').textbox({
            width:0,
            readonly:true   
        });
        $('#rca-form-po-nomor').textbox({
            width: 191,
            prompt:'Auto',
            readonly:true   
        });
        $('#rca-form-nomor').textbox({
            width: 191,
            prompt:'Auto',
            readonly:true   
        });
        $('#rca-form-catatan').textbox({
            width: 191 
        });
        $('#rca-form-id').next().hide();
        $('#rca-form-po-nomor').next().hide();
        $('#rca-form-po-id').next().hide();
        $('#rca-form-supplier-id').next().hide();
        $('#rca-total').textbox({
            width:300, 
            readonly:true   
        });
        $('#rca-btn-close').linkbutton({
            text: 'Close',
            iconCls:'fa fa-times fa-lg',
            onClick:function() {
                $('#po-add-dlg').dialog().dialog('close');
                $('#po-add-dlg').dialog().dialog('destroy');
            }
        });
        $('#rca-btn-save').linkbutton({
            text: 'Save',
            iconCls:'fa fa-save fa-lg',
            onClick:function() {
                rcaSave();
            }
        });
        $('#rca-btn-lunas').linkbutton({
            text: 'Lunas',
            iconCls:'fa fa-check fa-lg',
            onClick:function() {
                rcaLunas();
            }
        });
        $('#rca-total').textbox({
            readonly:true   
        });
        $('#rca-total').textbox('setValue',globalhargarca);
        $('#rca-btn-leftqty').linkbutton({
            iconCls:'fa fa-caret-left fa-lg',
            width:20,
            onClick:function() {
                var lqty=$('#rca-qty').numberbox('getValue');
                var selectedRow = $('#rca-grid').datagrid('getSelected');
                var indexRow = $('#rca-grid').datagrid('getRowIndex', selectedRow);
                globalhargarca=0;
                lqty=parseInt(lqty)-1
                if(lqty==0)lqty=0;
                $.each(globalrowrca,function(i,v){
                    if(v.itm_id==globalidrca)
                    {
                        v.qty=lqty;
                        v.total=parseInt(v.satuan0hrg)*v.qty*(1-(parseInt($('#rca-dsc').numberbox('getValue'))/100));
                    }
                    globalhargarca=globalhargarca+parseInt(v.total)
                })
                $('#rca-total').textbox('setValue',globalhargarca);
                $('#rca-grid').datagrid('loadData',globalrowrca);
                $('#rca-qty').numberbox('setValue',lqty);
                $('#rca-grid').datagrid('selectRow',indexRow);
            }
        });
        $('#rca-btn-rightqty').linkbutton({
            iconCls:'fa fa-caret-right fa-lg',
            width:20,
            onClick:function() {
                var rqty=$('#rca-qty').numberbox('getValue');
                var selectedRow = $('#rca-grid').datagrid('getSelected');
                var indexRow = $('#rca-grid').datagrid('getRowIndex', selectedRow);
                var globalhargarca=0;
                rqty=parseInt(rqty)+1
                $.each(globalrowrca,function(i,v){
                    if(parseInt(v.itm_id)==parseInt(globalidrca))
                    {
                        if(parseInt(v.itm_stok)<rqty){
                            $.messager.alert("Error kuantiti", "Maaf kuantiti item tidak boleh lebih dari stok");
                            rqty=parseInt(v.itm_stok)
                        }
                        v.qty=rqty;
                        v.total=parseInt(v.satuan0hrg)*parseInt(v.qty)*(1-(parseInt($('#rca-dsc').numberbox('getValue'))/100));
                    }
                    globalhargarca=parseInt(globalhargarca)+parseInt(v.total)
                })
                $('#rca-grid').datagrid('loadData',globalrowrca);
                $('#rca-total').textbox('setValue',globalhargarca);
                $('#rca-qty').numberbox('setValue',rqty);
                $('#rca-grid').datagrid('selectRow',indexRow);
            }
        });
        $('#rca-btn-leftdsc').linkbutton({
            iconCls:'fa fa-caret-left fa-lg',
            width:20,  
            onClick:function() {
                var ldsc=$('#rca-dsc').numberbox('getValue');
                var selectedRow = $('#rca-grid').datagrid('getSelected');
                var indexRow = $('#rca-grid').datagrid('getRowIndex', selectedRow);
                globalhargarca=0;
                ldsc=parseInt(ldsc)-1
                if(ldsc==0)ldsc=0;
                $.each(globalrowrca,function(i,v){
                    if(v.itm_id==globalidrca)
                    {
                        v.diskon=ldsc;
                        v.total=parseInt(v.satuan0hrg)*(parseInt($('#rca-qty').numberbox('getValue')))*(1-(v.diskon/100));
                    }
                    globalhargarca=globalhargarca+parseInt(v.total)
                })
                $('#rca-total').textbox('setValue',globalhargarca);
                $('#rca-grid').datagrid('loadData',globalrowrca);
                $('#rca-dsc').numberbox('setValue',ldsc);
                $('#rca-grid').datagrid('selectRow',indexRow);
            }
        });
        $('#rca-btn-rightdsc').linkbutton({
            iconCls:'fa fa-caret-right fa-lg',
            width:20,
            onClick:function() {
                var rdsc=$('#rca-dsc').numberbox('getValue');
                var selectedRow = $('#rca-grid').datagrid('getSelected');
                var indexRow = $('#rca-grid').datagrid('getRowIndex', selectedRow);
                globalhargarca=0;
                rdsc=parseInt(rdsc)+1
                $.each(globalrowrca,function(i,v){
                    if(v.itm_id==globalidrca)
                    {
                        v.diskon=rdsc;
                        v.total=parseInt(v.satuan0hrg)*(parseInt($('#rca-qty').numberbox('getValue')))*(1-(v.diskon/100));
                    }
                    globalhargarca=globalhargarca+parseInt(v.total)
                })
                $('#rca-total').textbox('setValue',globalhargarca);
                $('#rca-grid').datagrid('loadData',globalrowrca);
                $('#rca-dsc').numberbox('setValue',rdsc);
                $('#rca-grid').datagrid('selectRow',indexRow);
            }
        });
        $('#rca-qty').numberbox({
            prompt:'Qty',
            width:60,  
        });
        $('#rca-qty').textbox('textbox').bind('keydown', function(e){
            if (e.keyCode == 13){   // when press ENTER key, accept the inputed value.
                var selectedRow = $('#rca-grid').datagrid('getSelected');
                var indexRow = $('#rca-grid').datagrid('getRowIndex', selectedRow);
                globalhargarca=0;
                $.each(globalrowrca,function(i,v){
                    if(v.itm_id==globalidrca)
                    {
                        if(parseInt(v.itm_stok)<(parseInt($('#rca-qty').numberbox('getValue')))){
                            $.messager.alert("Error kuantiti", "Maaf kuantiti item tidak boleh lebih dari stok");
                            $('#rca-qty').numberbox('setValue',v.itm_stok);
                        }
                        v.qty=$('#rca-qty').numberbox('getValue');
                        v.total=parseInt(v.satuan0hrg)*(parseInt($('#rca-qty').numberbox('getValue')))*(1-(v.diskon/100));
                    }
                    globalhargarca+=parseInt(v.total)
                })
                $('#rca-total').textbox('setValue',globalhargarca);
                $('#rca-grid').datagrid('loadData',globalrowrca);
                $('#rca-grid').datagrid('selectRow',indexRow);
        }
        });
        $('#rca-dsc').numberbox({
            prompt:'Diskon',
            width:80,  
        });
        $('#rca-dsc').textbox('textbox').bind('keydown', function(e){
            if (e.keyCode == 13){   // when press ENTER key, accept the inputed value.
                var selectedRow = $('#rca-grid').datagrid('getSelected');
                var indexRow = $('#rca-grid').datagrid('getRowIndex', selectedRow);
                globalhargarca=0;
                $.each(globalrowrca,function(i,v){
                    if(v.itm_id==globalidrca)
                    {
                        v.dsc=$('#rca-dsc').numberbox('getValue');
                        v.total=parseInt(v.satuan0hrg)*(parseInt($('#rca-qty').numberbox('getValue')))*(1-(v.diskon/100));
                    }
                    globalhargarca+=parseInt(v.total)
                })
                $('#rca-total').textbox('setValue',globalhargarca);
                $('#rca-grid').datagrid('loadData',globalrowrca);
                $('#rca-grid').datagrid('selectRow',indexRow);
        }
        });
        $('#rca-btn-leftdscg').linkbutton({
            iconCls:'fa fa-caret-left fa-lg',
            width:20,  
            onClick:function() {
                var ldsc=$('#rca-dscg').numberbox('getValue');
                ldsc=parseInt(ldsc)-1;
                if(ldsc==0)ldsc=0;
                globaldiskon=ldsc;
                $('#rca-dscg').numberbox('setValue',ldsc);
            }
        });
        $('#rca-btn-rightdscg').linkbutton({
            iconCls:'fa fa-caret-right fa-lg',
            width:20,
            onClick:function() {
                var rdsc=$('#rca-dscg').numberbox('getValue');
                rdsc=parseInt(rdsc)+1;
                globaldiskon=rdsc;
                $('#rca-dscg').numberbox('setValue',rdsc);
            }
        });
        $('#rca-dscg').numberbox({
            prompt:'Diskon',
            width:80,  
        });
        $('#rca-dscg').numberbox('setValue',0)
        $('#rca-dscg').textbox('textbox').bind('keydown', function(e){
            if (e.keyCode == 13){   // when press ENTER key, accept the inputed value.
                globaldiskon=$('#rca-dscg').numberbox('getValue');
            }
        });
        $.post(getRestAPI("po/read"), {
            lok_id:globalConfig.login_data.data.kas_lok_id,
            orifields:'yes',
            loaditems:'yes',
        },
        function(data) {
            var obj=JSON.parse(data)
            var realdata=[];
            var realdatasearch=[];
            $.each(obj.data,function(i,v){
                if(v.po_status=='APPROVED')
                    realdata.push(v)
            })
            $('#rca-form-po').combobox({
                width:200,
                valueField:'po_id',
                textField:'po_nomor',
                panelHeight:'auto',
                panelMaxHeight:200,
                iconCls:'',
                mode:'remote',
                method:'post',
                data:realdata,
                formatter:function(row) {
                    return '<div class="row" style="padding-bottom:3px;">'+
                                '<div class="col-md-12" style="font-weight: bold;">'+
                                    row.po_nomor+
                                '</div>'+
                            '</div>'+
                            '<div class="row" style="padding-bottom:3px;">'+
                                '<div class="col-md-12" style="color:#ff0000">'+
                                    row.sup_nama+
                                '</div>'+
                        '</div>'+               
                    '</div>'
                },  
                onSelect:function(row) {
                    console.log(row)
                    $.each(row.poitems,function(i,v){
                        v.itm_id=v.poi_itm_id;
                        v.satuan0=v.poi_satuan0;
                        v.satuan1=v.poi_satuan1;
                        v.satuan0hpp=v.poi_satuan0hpp;
                        v.satuan1hpp=v.poi_satuan1hpp;
                        v.satuan0hrg=v.poi_satuan0hrg;
                        v.satuan1hrg=v.poi_satuan1hrg;
                        v.total=v.poi_total;
                        v.diskon=0;
                        globalrowrca.push(v)
                    })
                    $('#rca-grid').datagrid('loadData',row.poitems);
                    $('#rca-total').textbox('setValue',row.po_total);
                    globalhargarca=row.po_total;
                    globalidrca=row.itm_id;
                    $('#rca-form-po-id').textbox('setValue',row.po_id)
                    $('#rca-form-catatan').textbox('setValue',row.po_catatan)
                },
                onChange:function(row,index) {
                    // $.post(getRestAPI("po/read"), {
                    //     lok_id:globalConfig.login_data.data.kas_lok_id,
                    //     orifields:'yes',
                    //     q:row
                    // },
                    // function(data) {
                    //     var obj=JSON.parse(data)
                    //     realdatasearch=[]
                    //     $.each(obj.data,function(i,v){
                    //         if(v.po_status=='APPROVED')
                    //             realdatasearch.push(v)
                    //     })
                    //     $('#rca-form-po').combobox('loadData',realdatasearch)

                    // })
                },
            });
        })
        
        $('#rca-grid').datagrid({
            border:false,
            toolbar:'#rca-grid-tb',
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
                    return '<div class="row" style="padding-bottom:3px;">'+
                            '<div class="col-md-12" style="font-weight: bold;">'+
                                row.itm_nama+
                            '</div>'+
                        '</div>'+
                        '<div class="row" style="padding-bottom:3px;">'+
                            '<div class="col-md-12">'+
                                row.qty+' '+row.poi_satuan0+' @ Rp.'+currencyFormat(row.poi_satuan0hrg)+',00'
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
            data:globalrowrca,
            onSelect:function(index,row){
                $('#rca-qty').numberbox('setValue',parseInt(row.qty))
                $('#rca-dsc').numberbox('setValue',parseInt(row.diskon));
                globalidrca=row.itm_id
            },
            onLoadSuccess:function(data) {

            }
        });
        function rcaSave(){
            $.ajax({
                type:'POST',
                data:{rows:globalrowrca,
                    rcv_po_id:$('#rca-form-po-id').textbox('getValue'),
                    rcv_total:parseInt(globalhargarca)*(1-(parseInt($('#rca-dscg').numberbox('getValue'))/100)),
                    rcv_diskon:$('#rca-dscg').numberbox('getValue'),
                    rcv_kas_id:globalConfig.login_data.data.kas_id,
                    rcv_lok_id:globalConfig.login_data.data.kas_lok_id,
                    rcv_catatan:$('#rca-form-catatan').textbox('getValue'),
                    rcv_status:null
                },
                url:getRestAPI('receive/save'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    console.log(retval)
                    $('#rca-form-id').textbox('setValue',obj.rcv_id);
                    $('#rca-form-nomor').textbox('setValue',obj.rcv_nomor);
                }
            })
        }
        function rcaLunas(){
            $.ajax({
                type:'POST',
                data:{
                    rcv_id:$('#rca-form-id').textbox('getValue'),
                    
                },
                url:getRestAPI('receive/pay'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                }
            })
        }
        function rcaClearKustomer(){
            indexrca=-1;
            $('#rca-form-nama').textbox('setValue','');
            $('#rca-form-stok').numberbox('setValue','');
            $('#rca-form-harga').numberbox('setValue','');
            $('#rca-form-kode').textbox('setValue','');
            $('#rca-form-satuan').textbox('setValue','');
            $('#rca-form-gambar').textbox('setValue','');
            $('#rca-form-gambar-edit').textbox('setValue','');
            $('#rca-form-harga-awal').numberbox('setValue','');
            $('#rca-grid').datagrid('unselectRow', indexrca);
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