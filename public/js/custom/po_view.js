// Minus satu artinya mode lihat
// 0 berarti mode tambah
// selain itu berarti sedang ada record yang diedit
var poEditedId = -1;
var poiEditedId = -1;
var poSelectedPOId = null;
var poControlHeight = 24;

// Ini untuk menandai apakah event onChange pada textbox dan combobox
// ditrigger oleh code atau oleh user. Defaultnya oleh user
var po_changeByUser = true;
var poi_changeByUser = true;

$(function() {
    $('#po-btn-add').linkbutton({
        height:poControlHeight,
        onClick:function() {poAdd();}
    });
    $('#po-btn-save').linkbutton({
        disabled:true,
        height:poControlHeight,
        onClick:function() {poSave();}
    });
    $('#po-btn-cancel').linkbutton({
        disabled:true,
        height:poControlHeight,
        onClick:function() {poCancel();}
    });
    $('#po-btn-del').linkbutton({
        disabled:true,
        height:poControlHeight,
        onClick:function() {poDelete();}
    });
    $('#po-form-id').textbox({
        width:120,
        height:poControlHeight,
        prompt:'auto',
        readonly:true
    });
    $('#po-form-kode').textbox({
        width:120,
        height:poControlHeight,
        prompt:'auto',
        readonly:true
    });
    $('#po-form-supplier').combobox({
        width:200,
        valueField:'sup_id',
        textField:'sup_nama',
        height:poControlHeight,
        editable:false,
        panelHeight:'auto',
        panelMaxHeight:200,
        queryParams:{
            db:getDB()
        },
        url:getRestAPI('supplier/read'),
        onChange:function() {setEdited()},
        keyHandler:$.extend({}, $.fn.combobox.defaults.keyHandler, {
            down:function(e) {
                $(this).combobox('showPanel');
                $.fn.combobox.defaults.keyHandler.down.call(this,e);
            },
            enter:function(e) {
                $(this).combobox('hidePanel');
                $.fn.combobox.defaults.keyHandler.down.call(this,e);
                $('#po-form-tgorder').textbox('textbox').focus();
            }
        })
    });
    $('#po-form-tgorder').datebox({
        width:120,
        editable:false,
        height:poControlHeight,
        onChange:function() {setEdited()},
        inputEvents:$.extend({}, $.fn.textbox.defaults.inputEvents, {
            keypress:function(e) {
                if (e.which == 13) // enter
                    $('#po-form-supplier').textbox('textbox').focus();
            }
        })
    });
    $('#po-form-status').textbox({
        width:120,
        height:poControlHeight,
        prompt:'auto',
        readonly:true
    });
    $('#po-grid').datagrid({
        border:false,
        toolbar:'#po-grid-tb',
        fit:true,
        singleSelect:true,
        pagination:true,
        columns:[[{
            field:'po_id',
            title:'ID',
            resizable:false,
            width:90
        },{
            field:'po_kode',
            title:'Nomor PO',
            resizable:false,
            width:110
        },{
            field:'po_sup_id',
            title:'Supplier',
            formatter:function(value, row) {return row.sup_nama},
            resizable:false,
            width:200
        },{
            field:'po_tgorder',
            title:'Tgl Order',
            resizable:false,
            width:95
        },{
            field:'po_status',
            title:'Status',
            resizable:false,
            width:100
        }]],
        queryParams:{
            db:getDB()
        },
        url:getRestAPI('po/read'),
        onLoadSuccess:function(data) {
            if (data.length)
                $('#po-grid').datagrid('selectRow', 0);
            else {
                po_changeByUser = false;
                $('#po-btn-del').linkbutton('disable');
                $('#po-form-supplier').combobox('setValue', '');
                $('#po-form-supplier').combobox('readonly', true);
                $('#po-form-tgorder').datebox('setValue', '');
                $('#po-form-tgorder').datebox('readonly', true);
                po_changeByUser = true;
            }
        },
        onSelect:function(index, row) {
            po_changeByUser = false;
            $('#po-form-id').textbox('setValue', row.po_id);
            $('#po-form-kode').textbox('setValue', row.po_kode);
            $('#po-form-supplier').combobox('setValue', row.po_sup_id);
            $('#po-form-tgorder').datebox('setValue', row.po_tgorder);
            $('#po-form-status').textbox('setValue', row.po_status);
            $('#po-btn-del').linkbutton('enable');
            $('#po-form-supplier').combobox('readonly', false);
            $('#po-form-tgorder').datebox('readonly', false);
            po_changeByUser = true;
            $('#poi-grid').datagrid('load',{po_id:row.po_id});
            $('#poi-region').panel('setTitle', 'Daftar item permintaan pembelian (PO items) : '+row.po_kode);
        },
        onRowContextMenu:function(e,index,row) {
            if (row) {
                $('#po-grid').datagrid('selectRow', index);
                e.preventDefault();
                $('#po-menu').menu('show',{
                    left:e.pageX,
                    top:e.pageY,
                    onClick:function(item) {
                        switch(item.id) {
                        case 'po-mnu-rcv':rcvAdd(row.po_id);
                        }
                    }
                });
            }
        }
    });
    
    $('#po-search').searchbox({
        prompt:'Ketik kunci pencarian',
        height:poControlHeight,
        width:200,
        searcher:function(value) {
            $('#po-grid').datagrid('reload', {
                key_val:value,
                db:getDB()
            });
        }
    });

    $('#poi-btn-save').linkbutton({
        disabled:true,
        height:poControlHeight,
        onClick:function() {poiSave();}
    });
    $('#poi-btn-cancel').linkbutton({
        disabled:true,
        height:poControlHeight,
        onClick:function() {poiCancel();}
    });
    $('#poi-btn-del').linkbutton({
        disabled:true,
        height:poControlHeight,
        onClick:function() {poiDelete();}
    });
    $('#poi-form-harga').numberbox({
        width:120,
        height:poControlHeight,
        disabled:true,
        onChange:function() {setPoiEdited()},
        inputEvents:$.extend({}, $.fn.textbox.defaults.inputEvents, {
            keypress:function(e) {
                if (e.which == 13) // enter
                    $('#poi-form-qty').textbox('textbox').focus();
            }
        })
    });
    $('#poi-form-qty').numberbox({
        width:50,
        height:poControlHeight,
        disabled:true,
        onChange:function() {setPoiEdited()},
        inputEvents:$.extend({}, $.fn.textbox.defaults.inputEvents, {
            keypress:function(e) {
                if (e.which == 13) // enter
                    $('#poi-form-harga').textbox('textbox').focus();
            }
        })
    });
    $('#poi-grid').datagrid({
        border:false,
        fit:true,
        singleSelect:true,
        toolbar:'#poi-grid-tb',
        columns:[[{
            field:'poi_id',
            title:'ID',
            resizable:false,
            width:120
        },{
            field:'poi_po_id',
            title:'POID',
            resizable:false,
            width:100
        },{
            field:'poi_obt_id',
            title:'Obat',
            formatter:function(value, row) {return row.obt_nama},
            resizable:false,
            width:200
        },{
            field:'poi_harga',
            title:'Harga',
            align:'right',
            resizable:false,
            width:120
        },{
            field:'poi_qty',
            title:'Qty',
            align:'right',
            resizable:false,
            width:50
        },{
            field:'poi_total',
            title:'Total',
            align:'right',
            resizable:false,
            width:120
        }]],
        queryParams:{
            db:getDB()
        },
        url:getRestAPI('po/read_items'),
        onSelect:function(index, row) {
            $('#poi-form-harga').numberbox('enable');
            $('#poi-form-qty').numberbox('enable');
            $('#poi-btn-del').linkbutton('enable');
            poi_changeByUser = false;
            $('#poi-form-harga').numberbox('setValue', row.poi_harga);
            $('#poi-form-qty').numberbox('setValue', row.poi_qty);
            poi_changeByUser = true;
        }
    });

    function setEdited() {
        if (poEditedId == -1 && po_changeByUser) {
            poEditedId = $('#po-form-id').textbox('getValue');
            setEnableDisable();
        }
    }

    function setPoiEdited() {
        if (poiEditedId == -1 && poi_changeByUser) {
            var row = $('#poi-grid').datagrid('getSelected');
            poiEditedId = row.poi_id;
            setPoiEnableDisable();
        }
    }

    function poAdd() {
        po_changeByUser = false;
        var today = new Date();
        $('#po-form-id').textbox('setValue', '');
        $('#po-form-kode').textbox('setValue', '');
        $('#po-form-supplier').combobox('select', 10);
        $('#po-form-supplier').combobox('readonly', false);
        $('#po-form-tgorder').datebox('setValue', today.toLocaleDateString());
        $('#po-form-tgorder').datebox('readonly', false);
        $('#po-form-status').textbox('setValue', '');
        po_changeByUser = true;
        poEditedId = 0; // mode tambah
        setEnableDisable();
        $('#po-form-supplier').textbox('textbox').focus();
    }

    function poSave() {
        if (isDemo()) return;
        var data = {
            po_id:poEditedId,
            po_sup_id:$('#po-form-supplier').combobox('getValue'),
            po_tgorder:$('#po-form-tgorder').datebox('getValue'),
            po_status:$('#po-form-status').textbox('getValue'),
            db:getDB()
        };
        $.ajax({
            type:'POST',
            data:data,
            url:getRestAPI('po/save'),
            success:function(retval) {
                var obj = JSON.parse(retval);
                if (obj.status == 'success') {
                    if (poEditedId == 0) {
                        $('#po-grid').datagrid('insertRow', {
                            index:0,
                            row:obj.row
                        });
                        $('#po-grid').datagrid('selectRow', 0);
                    }
                    else {
                        var selectedRow = $('#po-grid').datagrid('getSelected');
                        var index = $('#po-grid').datagrid('getRowIndex', selectedRow);
                        $('#po-grid').datagrid('updateRow', {
                            index:index,
                            row:obj.row
                        });
                        $('#po-grid').datagrid('selectRow', index);
                    }
                }
                else
                    alert(obj.errmsg);
                poEditedId = -1;
                setEnableDisable();
            }
        });
    }

    function rcvAdd(po_id) {
        if (isDemo()) return;
        $('#inv-tab').tabs('select', 1);
        $.ajax({
            type:'POST',
            data:{
                po_id:po_id,
                db:getDB()
            },
            url:getRestAPI('receive/add'),
            success:function(retval) {
                var obj = JSON.parse(retval);
                if (obj.status == 'success') {
                    var selectedRow = $('#po-grid').datagrid('getSelected');
                    var index = $('#po-grid').datagrid('getRowIndex', selectedRow);
                    $('#po-grid').datagrid('updateRow', {
                        index:index,
                        row:obj.po_row
                    });
                    $('#po-grid').datagrid('selectRow', index);
                    $('#rcv-grid').datagrid('insertRow', {
                        index:0,
                        row:obj.row
                    });
                    $('#rcv-grid').datagrid('selectRow', 0);
                }
                else
                    alert(obj.errmsg);
            }
        });
    }

    function poCancel() {
        poEditedId = -1;
        setEnableDisable();
        var row = $('#po-grid').datagrid('getSelected');
        if (row)
            $('#po-grid').datagrid('selectRow', $('#po-grid').datagrid('getRowIndex', row));
        else {
            po_changeByUser = false;
            $('#po-btn-del').linkbutton('disable');
            $('#po-form-id').textbox('setValue', '');
            $('#po-form-kode').textbox('setValue', '');
            $('#po-form-supplier').combobox('setValue', '');
            $('#po-form-supplier').combobox('readonly', true);
            $('#po-form-tgorder').datebox('setValue', '');
            $('#po-form-tgorder').datebox('readonly', true);
            $('#po-form-status').textbox('setValue', '');
            po_changeByUser = true;
        }
    }

    function poDelete() {
        alert('Maaf menu ini belum bisa dipakai');
    }

    function poiSave() {
        if (isDemo()) return;
        var data = {
            poi_id:poiEditedId,
            poi_harga:$('#poi-form-harga').numberbox('getValue'),
            poi_qty:$('#poi-form-qty').numberbox('getValue'),
            db:getDB()
        };
        $.ajax({
            type:'POST',
            data:data,
            url:getRestAPI('po/save_item'),
            success:function(retval) {
                var obj = JSON.parse(retval);
                if (obj.status == 'success') {
                    var selectedRow = $('#poi-grid').datagrid('getSelected');
                    var index = $('#poi-grid').datagrid('getRowIndex', selectedRow);
                    $('#poi-grid').datagrid('updateRow', {
                        index:index,
                        row:obj.row
                    });
                    $('#poi-grid').datagrid('selectRow', index);
                }
                else
                    alert(obj.errmsg);
                poiEditedId = -1;
                setPoiEnableDisable();
            }
        });
    }

    function poiCancel() {
        poiEditedId = -1;
        setPoiEnableDisable();
        var row = $('#poi-grid').datagrid('getSelected');
        if (row)
            $('#poi-grid').datagrid('selectRow', $('#poi-grid').datagrid('getRowIndex', row));
        else {
            poi_changeByUser = false;
            $('#poi-btn-del').linkbutton('disable');
            $('#poi-form-harga').numberbox('setValue', '');
            $('#poi-form-harga').numberbox('disable');
            $('#poi-form-qty').numberbox('setValue', '');
            $('#poi-form-qty').numberbox('disable');
            poi_changeByUser = true;
        }
    }

    function poiDelete() {
        alert('Maaf menu ini belum bisa dipakai');
    }

    function setEnableDisable() {
        if (poEditedId >= 0) { // mode tambah atau edit
            $('#po-btn-add').linkbutton('disable');
            $('#po-btn-save').linkbutton('enable');
            $('#po-btn-cancel').linkbutton('enable');
            $('#po-btn-del').linkbutton('disable');
        }
        else { // mode lihat, tombol hapus akan enable kalau ada row yg terselect
            $('#po-btn-add').linkbutton('enable');
            $('#po-btn-save').linkbutton('disable');
            $('#po-btn-cancel').linkbutton('disable');
            $('#po-btn-del').linkbutton('enable');
        }
    }

    function setPoiEnableDisable() {
        if (poiEditedId >= 0) { // mode tambah atau edit
            $('#poi-btn-save').linkbutton('enable');
            $('#poi-btn-cancel').linkbutton('enable');
            $('#poi-btn-del').linkbutton('disable');
        }
        else { // mode lihat, tombol hapus akan enable kalau ada row yg terselect
            $('#poi-btn-save').linkbutton('disable');
            $('#poi-btn-cancel').linkbutton('disable');
            $('#poi-btn-del').linkbutton('enable');
        }
    }
});