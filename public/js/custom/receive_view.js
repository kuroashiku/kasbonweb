// Minus satu artinya mode lihat
// 0 berarti mode tambah
// selain itu berarti sedang ada record yang diedit
var rcvEditedId = -1;
var rcviEditedId = -1;
var rcviObtId = null;
var rcvControlHeight = 24;

// Ini untuk menandai apakah event onChange pada textbox dan combobox
// ditrigger oleh code atau oleh user. Defaultnya oleh user
var rcvChangeByUser = true;
var rcvi_changeByUser = true;

$(function() {
    $('#rcv-btn-save').linkbutton({
        disabled:true,
        height:rcvControlHeight,
        onClick:function() {rcvSave();}
    });
    $('#rcv-btn-cancel').linkbutton({
        disabled:true,
        height:rcvControlHeight,
        onClick:function() {rcvCancel();}
    });
    $('#rcv-btn-del').linkbutton({
        disabled:true,
        height:rcvControlHeight,
        onClick:function() {rcvDelete();}
    });
    $('#rcv-search').searchbox({
        prompt:'Ketik kunci pencarian',
        height:rcvControlHeight,
        width:200,
        searcher:function(value) {
            alert('Maaf fitur ini belum bisa dipakai');
        }
    });
    $('#rcv-form-id').textbox({
        width:120,
        height:rcvControlHeight,
        prompt:'auto',
        readonly:true
    });
    $('#rcv-form-kode').textbox({
        width:120,
        height:rcvControlHeight,
        prompt:'auto',
        readonly:true
    });
    $('#rcv-form-tgterima').datebox({
        width:120,
        height:rcvControlHeight,
        editable:false,
        onChange:function() {rcvSetEdited()},
        inputEvents:dateboxInputEvents('#rcv-form-tgterima', '#rcv-form-tglunas')
    });
    $('#rcv-form-tglunas').datebox({
        width:120,
        height:rcvControlHeight,
        editable:false,
        onChange:function() {rcvSetEdited()},
        inputEvents:dateboxInputEvents('#rcv-form-tglunas', '#rcv-form-kasbank')
    });
    $('#rcv-form-kasbank').combobox({
        width:120,
        height:rcvControlHeight,
        valueField:'coa_id',
        textField:'coa_nama',
        editable:false,
        panelHeight:'auto',
        panelMaxHeight:200,
        queryParams:{db:globalConfig.login_data.db},
        url:getRestAPI('coa/kasbank'),
        onChange:function() {rcvSetEdited()},
        keyHandler:comboboxKeyHandler('#rcv-form-tgterima')
    });
    $('#rcv-form-status').textbox({
        width:100,
        height:rcvControlHeight,
        prompt:'auto',
        readonly:true
    });
    $('#rcv-grid').datagrid({
        border:false,
        toolbar:'#rcv-grid-tb',
        fit:true,
        singleSelect:true,
        pagination:true,
        columns:[[{
            field:'rcv_id',
            title:'ID',
            resizable:false,
            hidden:true,
            width:90
        },{
            field:'rcv_kode',
            title:'No Receive',
            resizable:false,
            width:120
        },{
            field:'po_kode',
            title:'No PO',
            resizable:false,
            width:110
        },{
            field:'sup_nama',
            title:'Supplier',
            resizable:false,
            width:180
        },{
            field:'po_tgorder',
            title:'Tgl. order',
            resizable:false,
            width:85
        },{
            field:'rcv_tgterima',
            title:'Tgl. diterima',
            resizable:false,
            width:85
        },{
            field:'rcv_tglunas',
            title:'Tgl. lunas',
            resizable:false,
            width:85
        },{
            field:'rcv_coa_id',
            formatter:function(value,row) {return row.coa_nama},
            title:'Kas/Bank',
            resizable:false,
            width:150
        },{
            field:'rcv_status',
            title:'Status',
            resizable:false,
            width:100
        }]],
        queryParams:{
            db:getDB()
        },
        url:getRestAPI('receive/read'),
        onLoadSuccess:function(data) {
            if (data.length)
                $('#rcv-grid').datagrid('selectRow', 0);
            else {
                rcvChangeByUser = false;
                $('#rcv-form-id').textbox('setValue', '');
                $('#rcv-form-kode').textbox('setValue', '');
                $('#rcv-form-tgterima').datebox('setValue', '');
                $('#rcv-form-tglunas').datebox('setValue', '');
                $('#rcv-form-status').textbox('setValue', '');
                $('#rcv-form-kasbank').combobox('setValue', '');
                rcvChangeByUser = true;
                $('#rcv-btn-del').linkbutton('disable');
                $('#rcv-form-tgterima').datebox('readonly', true);
                $('#rcv-form-tglunas').datebox('readonly', true);
                $('#rcv-form-kasbank').combobox('readonly', true);
            }
        },
        onSelect:function(index, row) {
            rcvChangeByUser = false;
            $('#rcv-form-id').textbox('setValue', row.rcv_id);
            $('#rcv-form-kode').textbox('setValue', row.rcv_kode);
            $('#rcv-form-tgterima').datebox('setValue', row.rcv_tgterima);
            $('#rcv-form-tglunas').datebox('setValue', row.rcv_tglunas);
            $('#rcv-form-kasbank').combobox('setValue', row.rcv_coa_id);
            $('#rcv-form-status').textbox('setValue', row.rcv_status);
            rcvChangeByUser = true;
            $('#rcv-btn-del').linkbutton('enable');
            $('#rcv-form-tgterima').datebox('readonly', false);
            $('#rcv-form-tglunas').datebox('readonly', false);
            $('#rcv-form-kasbank').combobox('readonly', false);
            $('#rcvi-grid').datagrid('load',{rcv_id:row.rcv_id});
            $('#rcvi-form-obat').combobox('reload',getRestAPI('receive/ordered_items'));
            $('#rcvi-region').panel('setTitle', 'Daftar item penerimaan barang (Receive items) : '+row.rcv_kode);
        },
        onRowContextMenu:function(e,index,row) {
            if (row) {
                $('#rcv-grid').datagrid('selectRow', index);
                e.preventDefault();
                $('#rcv-menu').menu('show',{
                    left:e.pageX,
                    top:e.pageY,
                    onClick:function(item) {
                        switch(item.id) {
                        case 'rcv-mnu-item':rcviAdd();
                        }
                    }
                });
            }
        }
    });

    $('#rcvi-btn-save').linkbutton({
        disabled:true,
        height:rcvControlHeight,
        onClick:function() {rcviSave();}
    });
    $('#rcvi-btn-cancel').linkbutton({
        disabled:true,
        height:rcvControlHeight,
        onClick:function() {rcviCancel();}
    });
    $('#rcvi-form-harga').numberbox({
        width:120,
        disabled:true,
        height:rcvControlHeight,
        onChange:function() {rcviSetEdited()},
        inputEvents:$.extend({}, $.fn.textbox.defaults.inputEvents, {
            keypress:function(e) {
                if (e.which == 13) // enter
                    $('#rcvi-form-qty').textbox('textbox').focus();
            }
        })
    });
    $('#rcvi-form-qty').numberbox({
        width:50,
        disabled:true,
        height:rcvControlHeight,
        onChange:function() {rcviSetEdited()},
        inputEvents:$.extend({}, $.fn.textbox.defaults.inputEvents, {
            keypress:function(e) {
                if (e.which == 13) // enter
                    $('#rcvi-form-obat').textbox('textbox').focus();
            }
        })
    });
    $('#rcvi-grid').datagrid({
        border:false,
        fit:true,
        singleSelect:true,
        toolbar:'#rcvi-grid-tb',
        columns:[[{
            field:'rcvi_id',
            title:'ID',
            resizable:false,
            width:110
        },{
            field:'rcvi_rcv_id',
            title:'RCVID',
            resizable:false,
            width:100
        },{
            field:'rcvi_obt_id',
            title:'Obat',
            formatter:function(value, row) {return row.obt_nama},
            resizable:false,
            width:200
        },{
            field:'rcvi_harga',
            title:'Harga',
            align:'right',
            resizable:false,
            width:120
        },{
            field:'rcvi_qty',
            title:'Qty',
            align:'right',
            resizable:false,
            width:50
        },{
            field:'rcvi_total',
            title:'Total',
            align:'right',
            resizable:false,
            width:120
        },{
            field:'poi_harga',
            title:'Harga PO',
            align:'right',
            resizable:false,
            width:120
        },{
            field:'poi_qty',
            title:'Qty PO',
            align:'right',
            resizable:false,
            width:50
        }]],
        queryParams:{
            db:getDB()
        },
        url:getRestAPI('receive/read_items'),
        onSelect:function(index, row) {
            $('#rcvi-form-harga').numberbox('enable');
            $('#rcvi-form-qty').numberbox('enable');
            rcvi_changeByUser = false;
            $('#rcvi-form-harga').numberbox('setValue', row.rcvi_harga);
            $('#rcvi-form-qty').numberbox('setValue', row.rcvi_qty);
            rcvi_changeByUser = true;
            rcviObtId = row.rcvi_obt_id;
        },
        onRowContextMenu:function(e,index,row) {
            if (row) {
                $('#rcvi-grid').datagrid('selectRow', index);
                e.preventDefault();
                $('#rcvi-menu').menu('show',{
                    left:e.pageX,
                    top:e.pageY,
                    onClick:function(item) {
                        switch(item.id) {
                        case 'rcvi-mnu-item':rcviDelete();
                        }
                    }
                });
            }
        }
    });

    function rcvSetEdited() {
        if (rcvEditedId == -1 && rcvChangeByUser) {
            rcvEditedId = $('#rcv-form-id').textbox('getValue');
            rcvSetEnableDisable();
        }
    }

    function rcviSetEdited() {
        if (rcviEditedId == -1 && rcvi_changeByUser) {
            var row = $('#rcvi-grid').datagrid('getSelected');
            rcviEditedId = row.rcvi_id;
            setRcviEnableDisable();
        }
    }

    function rcvSave() {
        if (isDemo()) return;
        var data = {
            rcv_id:rcvEditedId,
            rcv_tgterima:$('#rcv-form-tgterima').datebox('getValue'),
            rcv_tglunas:$('#rcv-form-tglunas').datebox('getValue'),
            rcv_coa_id:$('#rcv-form-kasbank').combobox('getValue'),
            db:getDB()
        };
        $.ajax({
            type:'POST',
            data:data,
            url:getRestAPI('receive/save'),
            success:function(retval) {
                var obj = JSON.parse(retval);
                if (obj.status == 'success') {
                    if (rcvEditedId == 0) {
                        $('#rcv-grid').datagrid('insertRow', {
                            index:0,
                            row:obj.row
                        });
                        $('#rcv-grid').datagrid('selectRow', 0);
                    }
                    else {
                        var selectedRow = $('#rcv-grid').datagrid('getSelected');
                        var index = $('#rcv-grid').datagrid('getRowIndex', selectedRow);
                        $('#rcv-grid').datagrid('updateRow', {
                            index:index,
                            row:obj.row
                        });
                        $('#rcv-grid').datagrid('selectRow', index);
                    }
                }
                else
                    $.messager.alert(globalConfig.app_nama, obj.errmsg);
                rcvEditedId = -1;
                rcvSetEnableDisable();
            }
        });
    }

    function rcvCancel() {
        rcvEditedId = -1;
        rcvSetEnableDisable();
        var row = $('#rcv-grid').datagrid('getSelected');
        if (row)
            $('#rcv-grid').datagrid('selectRow', $('#rcv-grid').datagrid('getRowIndex', row));
        else {
            rcvChangeByUser = false;
            $('#rcv-btn-del').linkbutton('disable');
            $('#rcv-form-id').textbox('setValue', '');
            $('#rcv-form-kode').textbox('setValue', '');
            $('#rcv-form-tgterima').datebox('setValue', '');
            $('#rcv-form-tglunas').datebox('setValue', '');
            $('#rcv-form-kasbank').combobox('setValue', '');
            $('#rcv-form-status').textbox('setValue', '');
            rcvChangeByUser = true;
            $('#rcv-form-tgterima').datebox('readonly', true);
            $('#rcv-form-tglunas').datebox('readonly', true);
            $('#rcv-form-kasbank').combobox('readonly', true);
        }
    }

    function rcvDelete() {
        alert('Maaf menu ini belum bisa dipakai');
    }

    function rcviAdd() {
        if (isDemo()) return;
        var selectedRow = $('#rcv-grid').datagrid('getSelected');
        if (selectedRow) {
            $.ajax({
                type:'POST',
                data:{
                    rcv_id:selectedRow.rcv_id,
                    db:getDB()
                },
                url:getRestAPI('receive/ordered_items'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    if (obj.status == 'success') {
                        $('#rcvi-grid').datagrid('reload');
                        if ($('#rcvi-grid').datagrid('getData'))
                            $('#rcvi-grid').datagrid('selectRow', 0);
                    }
                    else
                        $.messager.alert(globalConfig.app_nama, obj.errmsg);
                    rcviEditedId = -1;
                    setRcviEnableDisable();
                }
            });
        }
        else $.messager.alert(globalConfig.app_nama,
            'Pilih salah satu penerimaan untuk bisa memasukkan item-item penerimaan',
            'info');
    }

    function rcviSave() {
        if (isDemo()) return;
        var data = {
            rcvi_id:rcviEditedId,
            rcvi_rcv_id: $('#rcv-form-id').textbox('getValue'),
            rcvi_harga:$('#rcvi-form-harga').numberbox('getValue'),
            rcvi_qty:$('#rcvi-form-qty').numberbox('getValue'),
            rcvi_obt_id: rcviObtId,
            db:getDB()
        };
        $.ajax({
            type:'POST',
            data:data,
            url:getRestAPI('receive/save_item'),
            success:function(retval) {
                var obj = JSON.parse(retval);
                if (obj.status == 'success') {
                    var selectedRow = $('#rcvi-grid').datagrid('getSelected');
                    var index = $('#rcvi-grid').datagrid('getRowIndex', selectedRow);
                    $('#rcvi-grid').datagrid('updateRow', {
                        index:index,
                        row:obj.row
                    });
                    $('#rcvi-grid').datagrid('selectRow', index);
                }
                else
                    $.messager.alert(globalConfig.app_nama, obj.errmsg);
                rcviEditedId = -1;
                setRcviEnableDisable();
            }
        });
    }

    function rcviCancel() {
        rcviEditedId = -1;
        setRcviEnableDisable();
        var row = $('#rcvi-grid').datagrid('getSelected');
        if (row)
            $('#rcvi-grid').datagrid('selectRow', $('#rcvi-grid').datagrid('getRowIndex', row));
        else {
            rcvi_changeByUser = false;
            $('#rcvi-form-harga').numberbox('setValue', '');
            $('#rcvi-form-harga').numberbox('disable');
            $('#rcvi-form-qty').numberbox('setValue', '');
            $('#rcvi-form-qty').numberbox('disable');
            rcvi_changeByUser = true;
        }
    }

    function rcviDelete() {
        if (isDemo()) return;
        var selectedRow = $('#rcvi-grid').datagrid('getSelected');
        if (selectedRow) $.ajax({
            type:'POST',
            data:{
                rcvi_id:selectedRow.rcvi_id,
                db:getDB()
            },
            url:getRestAPI('receive/delete_item'),
            success:function(retval) {
                var obj = JSON.parse(retval);
                if (obj.status == 'success')
                    $('#rcvi-grid').datagrid('reload');
                else
                    $.messager.alert(globalConfig.app_nama, obj.errmsg);
                rcviEditedId = -1;
                setRcviEnableDisable();
            }
        });
        else $.messager.alert(globalConfig.app_nama, 'Silahkan pilih dulu item yang akan dihapus');
    }

    function rcvSetEnableDisable() {
        if (rcvEditedId >= 0) { // mode tambah atau edit
            $('#rcv-btn-save').linkbutton('enable');
            $('#rcv-btn-cancel').linkbutton('enable');
            $('#rcv-btn-del').linkbutton('disable');
        }
        else { // mode lihat, tombol hapus akan enable kalau ada row yg terselect
            $('#rcv-btn-save').linkbutton('disable');
            $('#rcv-btn-cancel').linkbutton('disable');
            $('#rcv-btn-del').linkbutton('enable');
        }
    }

    function setRcviEnableDisable() {
        if (rcviEditedId >= 0) { // mode tambah atau edit
            $('#rcvi-btn-save').linkbutton('enable');
            $('#rcvi-btn-cancel').linkbutton('enable');
        }
        else { // mode lihat, tombol hapus akan enable kalau ada row yg terselect
            $('#rcvi-btn-save').linkbutton('disable');
            $('#rcvi-btn-cancel').linkbutton('disable');
        }
    }
});