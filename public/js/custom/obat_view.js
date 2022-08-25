var obatCategory = [
    {label:'Semua'},
    {label:'Obat'},
    {label:'Injeksi'},
    {label:'Generic'},
    {label:'Syrup'},
    {label:'BHP'},
    {label:'Hewan'},
    {label:'Lain-lain'}
];
var obtSearchKey = null;
var selectedCategory = obatCategory[0].label;


$(function() {
    $('#obt-grid').datagrid({
        border:false,
        singleSelect:true,
        toolbar:'#obt-grid-tb',
        pagination:true,
        pageSize:50,
        idField:'obt_id',
        editorHeight:22,
        fit:true,
        columns:[[{
            field:'obt_id',
            title:'ID',
            resizable:false,
            width:40
        },{
            field:'obt_kode',
            title:'Kode',
            resizable:false,
            width:100
        },{
            field:'obt_nama',
            title:'Nama',
            resizable:false,
            width:200
        },{
            field:'cbt_harga',
            title:'Harga',
            resizable:false,
            editor:{
                type:'numberbox'
            },
            align:'right',
            width:80,
            formatter: function(value, row) {return currencyFormat(row.cbt_harga)}
        },{
            field:'cbt_satuan',
            title:'Satuan',
            resizable:false,
            editor:{
                type:'textbox',
                options:{
                    required:true,
                    validType:'length[0,30]'
                }
            },
            width:50
        },{
            field:'cbt_stok',
            title:'Stok',
            resizable:false,
            align:'right',
            width:50
        },{
            field:'cbt_stokaman',
            title:'Stok Aman',
            resizable:false,
            editor:{
                type:'numberbox'
            },
            align:'right',
            width:80
        },{
            field:'cbt_tgstokopnam',
            title:'Tgl Opnam',
            resizable:false,
            editor:{
                type:'datebox'
            },
            width:110
        },{
            field:'cbt_stokopnam',
            title:'Stok Opnam',
            resizable:false,
            editor:{
                type:'numberbox'
            },
            align:'right',
            width:80
        },{
            field:'cbt_hargapokok',
            title:'Harga pokok',
            resizable:false,
            editor:{
                type:'numberbox'
            },
            align:'right',
            width:90,
            formatter: function(value, row) {return currencyFormat(row.cbt_hargapokok)}
        },{
            field:'cbt_keterangan',
            title:'Keterangan',
            resizable:false,
            width:300
        },{
            field:'cbt_lob_id',
            title:'Label',
            formatter:function(value, row) {return row.lob_nama},
            resizable:false,
            width:200
        }]],
        rowStyler:function(index, row) {
            if(row.obt_stok < row.obt_stokaman)
                return 'color:#ff0000;font-weight:bold';
        },
        queryParams:{
            db:getDB(),
            cbt_com_id:globalConfig.com_id,
        },
        url:getRestAPI('obat/browse'),
        onLoadSuccess:function(data) {
            if (data.rows.length > 0) $(this).datagrid('selectRow', 0);
        },
        onBeginEdit:function(index,row) {
            var dg = $(this);
            var ed = dg.datagrid('getEditors', index);
            console.log("ed = "+ed);
            if (!ed)
                return;
            var t;
            for(i=0;i<ed.length;i++) {
                t = $(ed[i].target);
                t.textbox('textbox').bind('keydown', function(e) {
                    if (e.keyCode == 13) saveObat();
                    else if (e.keyCode == 27) cancelObat();
                });
            }
        },
        onRowContextMenu:function(e,index,row) {
            if (row) {
                $('#obt-grid').datagrid('selectRow',index);
                e.preventDefault();
                $('#obt-menu').menu('show',{
                    left:e.pageX,
                    top:e.pageY,
                    onClick:function(item) {
                        switch(item.id) {
                        case 'obt-mnu-add':
                            var poRow = $('#po-grid').datagrid('getSelected');
                            if (poRow) {
                                if(poRow.po_status == "ORDER"){
                                    poiAdd(poRow, row);
                                }else{
                                    $.messager.alert('SiReDisH', 'Obat hanya bisa dimasukkan untuk PO dengan status ORDER','info');
                                }
                            }else{
                                $.messager.alert('SiReDisH', 'Pilih salah satu PO untuk bisa memasukkan obat','info');
                            }
                            break;
                        case 'obt-mnu-edit':
                            editObat();
                        }
                    }
                });
            }
        }
    });

     $('#obt-filter-ctg').combobox({
        width:90,
        editable:false,
        panelHeight:'auto',
        data:obatCategory,
        valueField:'label',
        textField:'label',
        onLoadSuccess:function(data) {
            if (data.length) {
                $(this).combobox('setValue', selectedCategory);
            }
        },
        onChange: function(){
            loadObat(obtSearchKey);
        }
    });

    $('#obt-search').searchbox({
        prompt:'Ketik kunci pencarian',
        width:150,
        searcher:function(value) {
            loadObat(value);
            obtSearchKey = value;
        }
    });

    var editingId;

    function editObat() {
        if (isDemo()) return;
        if (editingId != undefined){
            $('#obt-grid').datagrid('select', editingId);
            return;
        }
        var row = $('#obt-grid').datagrid('getSelected');
        if (row) {
            editingId = row.obt_id;
            var index = $('#obt-grid').datagrid('getRowIndex', editingId);
            $('#obt-grid').datagrid('beginEdit', index);
            var ed = $('#obt-grid').datagrid('getEditor', {index:row.id, field:'obt_kode'});
            $(ed.target).focus();
            $(ed.target).select();
        }
    }

    function cancelObat() {
        if (editingId != undefined){
            var index = $('#obt-grid').datagrid('getRowIndex', editingId);
            $('#obt-grid').datagrid('cancelEdit', index);
            editingId = undefined;
        }
    }
    
    function saveObat() {
        if (editingId != undefined){
            var index = $('#obt-grid').datagrid('getRowIndex', editingId);
            console.log("editingId = "+editingId);
            $('#obt-grid').datagrid('endEdit', editingId);
            editingId = undefined;
            var row = $('#obt-grid').datagrid('getSelected');
            row.db = getDB();
            console.log(row);
            $.ajax({
                type:'POST',
                data:row,
                url:getRestAPI('obat/save'),
                success: function(retval) {
                    var obj = JSON.parse(retval);
                    if(obj.status == 'success')
                        $('#obt-grid').datagrid('update',{
                            id: obj.row.id,
                            row: obj.row
                        });
                    else
                        $.messager.alert(globalConfig.app_nama, 'Proses simpan obat gagal');
                }
            });
        }
    }

    function poiAdd(poRow, row) {
        if (isDemo()) return;
        var data = {
            poi_po_id:poRow.po_id,
            poi_obt_id:row.obt_id,
            poi_harga:row.obt_harga,
            db:getDB()
        };
        $.ajax({
            type:'POST',
            data:data,
            url:getRestAPI('po/add_item'),
            success:function(retval) {
                var obj = JSON.parse(retval);
                if (obj.status == 'success') {
                    $('#poi-grid').datagrid('appendRow', obj.row);
                    var idx = $('#poi-grid').datagrid('getRowIndex', obj.row);
                    $('#poi-grid').datagrid('selectRow', idx);
                }
                else
                    alert(obj.errmsg);
            }
        });
    }

    function loadObat(keyword){
        selectedCategory = $('#obt-filter-ctg').combobox('getValue');
        console.log("selectedCategory = "+selectedCategory);
        $('#obt-grid').datagrid('reload', {
            key_val:keyword?keyword : undefined,
            category: selectedCategory === "Semua"? undefined : selectedCategory,
            cbt_com_id:globalConfig.com_id ,
            db:getDB()
        });
    }

});