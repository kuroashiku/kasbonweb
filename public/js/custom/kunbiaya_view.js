// Minus satu artinya mode lihat
// 0 berarti mode tambah
// selain itu berarti sedang ada record yang diedit
var kbiEditedIdx = -1;
var kbiClickedIdx = -1;
var kbiSelectedKunId = null;
var kbiFirstLoad = true;

$(function() {
    $('#kbi-btn-add').linkbutton({
        text:'Tambah',
        height:24,
        iconCls:'fa fa-plus-circle',
        onClick:function() {kbiAdd();}
    });
    $('#kbi-btn-save').linkbutton({
        text:'Simpan',
        height:24,
        iconCls:'fa fa-check-circle',
        disabled:true,
        onClick:function() {kbiSave();}
    });
    $('#kbi-btn-cancel').linkbutton({
        text:'Batal',
        height:24,
        iconCls:'fa fa-times-circle',
        disabled:true,
        onClick:function() {kbiCancel();}
    });
    $('#kbi-btn-del').linkbutton({
        text:'Hapus',
        height:24,
        onClick:function() {kbiDelete();}
    });
    $('#kbi-search').searchbox({
        prompt:'Ketik kunci pencarian',
        width:200,
        height:24,
        searcher:function(value) {
            alert('Maaf fitur ini belum bisa dipakai');
        }
    });
    $('#kbi-grid').datagrid({
        border:false,
        singleSelect:true,
        pagination:true,
        pageSize:50,
        toolbar:'#kbi-grid-tb',
        idField:'kbi_id',
        editorHeight:22,
        fit:true,
        columns:[[{
            field:'kbi_id',
            title:'ID',
            resizable:false,
            width:110
        },{
            field:'kbi_tanggal',
            title:'Tanggal',
            resizable:false,
            editor:{
                type:'datebox'
            },
            width:110
        },{
            field:'kbi_jns_id',
            title:'Jenis biaya',
            resizable:false,
            width:100,
            formatter:function(value,row) {
                var str = '';
                switch(row.kbi_jns_id) {
                    case 'D':str = 'Dokter';break;
                    case 'P':str = 'Perawat';break;
                    case 'T':str = 'Tindakan';break;
                    case 'O':str = 'Obat';break;
                    case 'I':str = 'Injeksi';break;
                    case 'L':str = 'Laborat';break;
                    case 'R':str = 'Radiologi';break;
                    case 'K':str = 'Kamar';
                }
                return str;
            },
            editor:{
                type:'combobox',
                options:{
                    valueField:'jns_id',
                    textField:'jns_nama',
                    // tidak membaca database tapi json di model
                    url:getRestAPI('master/jenisbiaya')+'?kamaronly='+globalConfig.login_data.kamaronly,
                    panelHeight:'auto',
                    editable:false,
                    required:false,
                    onSelect:function(record) {kbiJenisBiayaChange(record)},
                    keyHandler:$.extend({}, $.fn.combobox.defaults.keyHandler, {
                        down:function(e) {
                            $(this).combobox('showPanel');
                            $.fn.combobox.defaults.keyHandler.down.call(this,e);
                        }
                    })
                }
            }
        },{
            field:'kbi_bea_id',
            title:'Nama biaya',
            resizable:false,
            width:200,
            formatter:function(value,row) {
                return row.bea_nama;
            },
            editor:{
                type:'combobox',
                options:{
                    id:'kbi-form-biaya',
                    valueField:'bea_id',
                    textField:'bea_nama',
                    queryParams:{
                        db:getDB(),
                        lok_jenis:globalConfig.lok_jenis
                    },
                    url:getRestAPI('master/biaya'),
                    mode:'remote',
                    panelHeight:'auto',
                    panelMaxHeight:200,
                    searching:false,
                    editable:true,
                    required:false,
                    keyHandler:$.extend({}, $.fn.combobox.defaults.keyHandler, {
                        down:function(e) {
                            $(this).combobox('showPanel');
                            $.fn.combobox.defaults.keyHandler.down.call(this,e);
                        }
                    }),
                    onBeforeLoad:function(param) {
                        if(param.q && !$(this).combobox('options').searching)
                            $(this).combobox('options').searching = true;
                    },
                    onClick:function(record) {
                        if ($(this).combobox('options').searching)
                            $(this).combobox('options').searching = false;
                    },
                    onLoadSuccess:function() {
                        if(!$(this).combobox('options').searching) {
                            var row = $('#kbi-grid').datagrid('getSelected');
                            var data = $(this).combobox('getData');
                            if (data.length) {
                                var exists = false;
                                for(var i=0;i<data.length;i++) {
                                    if (data[i].bea_id == row.kbi_bea_id) {
                                        exists = true;
                                        break;
                                    }
                                }
                                if (!exists) {
                                    $(this).combobox('clear');
                                    $(this).textbox('textbox').tooltip({
                                        content:'Nama biaya sebelumnya yaitu [<b>'+row.bea_nama+
                                            '</b>] dikosongkan,<br>karena sudah tidak sesuai dengan daftar.'+
                                            '<br>Silahkan disesuaikan'
                                    });
                                }
                                else {
                                    $(this).combobox('setValue', row.kbi_bea_id);
                                    $(this).textbox('textbox').tooltip('destroy');
                                }
                            }
                        }
                    },
                    onSelect:function(record) {
                        var ed = $('#kbi-grid').datagrid('getEditor', {index:kbiEditedIdx, field:'kbi_harga'});
                        $(ed.target).numberbox('setValue', record.bea_harga);
                        var ed = $('#kbi-grid').datagrid('getEditor', {index:kbiEditedIdx, field:'kbi_obtstok'});
                        $(ed.target).textbox('setValue', record.cbt_stok+'-'+record.cbt_stokaman);
                        var ed = $('#kbi-grid').datagrid('getEditor', {index:kbiEditedIdx, field:'kbi_namaobat'});
                        $(ed.target).textbox('setValue', record.bea_nama);
                        if(record.cbt_stok<=0){
                            //$.messager.alert(globalConfig.app_nama, "Obat "+record.bea_nama+" Kehabisan Stok");
                            kbiObatChange();
                            if (kbiEditedIdx == 0){
                                
                                // $('#kbi-grid').datagrid('deleteRow', kbiEditedIdx);
                                // kbiEditedIdx = -1;
                                // kbiSetEnableDisable();
                            }
                            else{
                                // $('#kbi-grid').datagrid('cancelEdit', kbiEditedIdx);
                                // kbiEditedIdx = -1;
                                // kbiSetEnableDisable();
                            }       
                        }
                        else if(record.cbt_stokaman>=record.cbt_stok){
                            $.messager.alert(globalConfig.app_nama, "Obat "+record.bea_nama+" Hampir Habis");       
                        }
        
                        
                        kbiCalculateTotal();
                    }
                }
            }
        },{
            field:'kbi_sdm_id',
            title:'Nama dokter',
            resizable:false,
            width:200,
            formatter:function(value,row) {
                var str = row.sdm_nama;
                if (row.kbi_jns_id != 'O' && row.kbi_jns_id != 'I')
                    str = '<div style="background-color:#98b6cf">&nbsp;</div>';
                return str;
            },
            editor:{
                type:'combobox',
                options:{
                    valueField:'sdm_id',
                    textField:'sdm_nama',
                    queryParams:{
                        db:getDB(),
                        com_id:globalConfig.com_id
                    },
                    url:getRestAPI('sdm/dokter'),
                    panelHeight:'auto',
                    panelMaxHeight:200,
                    editable:false,
                    required:false,
                    keyHandler:$.extend({}, $.fn.combobox.defaults.keyHandler, {
                        down:function(e) {
                            $(this).combobox('showPanel');
                            $.fn.combobox.defaults.keyHandler.down.call(this,e);
                        }
                    })
                }
            }
        },{
            field:'kbi_dos_id',
            title:'Dosis',
            resizable:false,
            width:80,
            formatter:function(value,row) {
                var str = value;
                if (row.kbi_jns_id != 'O' && row.kbi_jns_id != 'I')
                    str = '<div style="background-color:#98b6cf">&nbsp;</div>';
                return str;
            },
            editor:{
                type:'combobox',
                options:{
                    valueField:'dos_id',
                    textField:'dos_nama',
                    url:getRestAPI('master/dosisobat'), // tidak membaca database tapi json di model
                    panelHeight:'auto',
                    panelMaxHeight:200,
                    editable:false,
                    required:false,
                    keyHandler:$.extend({}, $.fn.combobox.defaults.keyHandler, {
                        down:function(e) {
                            $(this).combobox('showPanel');
                            $.fn.combobox.defaults.keyHandler.down.call(this,e);
                        }
                    })
                }
            }
        },{
            field:'kbi_harga',
            title:'Harga',
            align:'right',
            editor:{
                type:'numberbox',
                options:{
                    readonly:true
                }
            },
            resizable:false,
            width:100
        },{
            field:'kbi_obtstok',
            title:'Stok',
            formatter:function(value,row) {
                var str = value;
                if (row.kbi_jns_id != 'O')
                    str = '<div style="background-color:#98b6cf">&nbsp;</div>';
                return str;
            },
            editor:{
                type:'textbox',
                options:{
                    readonly:true
                }
            },
            width:120,
            hidden:true
        },{
            field:'kbi_namaobat',
            title:'Nama Obat',
            formatter:function(value,row) {
                var str = value;
                if (row.kbi_jns_id != 'O')
                    str = '<div style="background-color:#98b6cf">&nbsp;</div>';
                return str;
            },
            editor:{
                type:'textbox',
                options:{
                    readonly:true
                }
            },
            width:120,
            hidden:true
        },{
            field:'kbi_obt_qty',
            title:'Qty',
            align:'right',
            formatter:function(value,row) {
                var str = value;
                if (row.kbi_jns_id != 'O')
                    str = '<div style="background-color:#98b6cf">&nbsp;</div>';
                return str;
            },
            editor:{
                type:'numberbox',
                options:{
                    onChange:function() {kbiCalculateTotal()}
                }
            },
            resizable:false,
            width:50
        },{
            field:'kbi_kmr_tgcheckout',
            title:'Tgl. checkout',
            formatter:function(value,row) {
                var str = value;
                if (row.kbi_jns_id != 'K')
                    str = '<div style="background-color:#98b6cf">&nbsp;</div>';
                return str;
            },
            editor:{
                type:'datebox',
                options:{
                    onChange:function() {kbiCalculateTotal()}
                }
            },
            resizable:false,
            width:110
        },{
            field:'kbi_total',
            title:'Total',
            align:'right',
            editor:{
                type:'numberbox',
                options:{
                    readonly:true
                }
            },
            resizable:false,
            width:100
        }]],
        queryParams:{
            db:getDB(),
            lok_jenis:globalConfig.lok_jenis
        },
        url:getRestAPI('kunbiaya/read'),
        onSelect:function(index,row){
            if (row.kbi_jns_id=='K' &&  (globalConfig.login_data.kamaronly==undefined || 
                globalConfig.login_data.kamaronly!=1)){
                $('#yan-tab').tabs('getTab', 'Evaluasi Dokter').panel('options').tab.show();
                $('#yan-tab').tabs('getTab', 'Evaluasi Perawat').panel('options').tab.show();
            }
            else{
                $('#yan-tab').tabs('getTab', 'Evaluasi Dokter').panel('options').tab.hide();
                $('#yan-tab').tabs('getTab', 'Evaluasi Perawat').panel('options').tab.hide();
            }
        },
        onLoadSuccess:function(data) {
            if (data.rows.length > 0) {
                $(this).datagrid('selectRow', 0);
                kbiClickedIdx = 0;
            }
            if (kbiFirstLoad) {
                kbiFirstLoad = false;
                $('#yan-grid').datagrid('selectRow', 0);
            }
        },
        onBeginEdit:function(index,row) {
            var ed = $(this).datagrid('getEditors', index);
            if (!ed)
                return;
            var t;
            for(i=0;i<ed.length;i++) {
                t = $(ed[i].target);
                t.textbox('textbox').bind('keydown', function(e) {
                    if (e.keyCode == 13) kbiSave();
                    else if (e.keyCode == 27) kbiCancel();
                });
            }
        },
        onClickCell:function(index,field,value) {
            var row = $(this).datagrid('getSelected');
            var editable = false;
            if (kbiClickedIdx == -1)
                kbiClickedIdx = index;
            else if (kbiClickedIdx != index) {
                if (kbiEditedIdx != -1) kbiCancel();
                kbiClickedIdx = index;
            }
            else if (globalConfig.login_data.kamaronly == undefined ||
                globalConfig.login_data.kamaronly != 1)
                editable = true;
            else if (globalConfig.login_data.kamaronly != undefined 
                && globalConfig.login_data.kamaronly == 1
                && row.kbi_jns_id == 'K')
                editable = true;
            if (editable) {
                var editable = false;
                if ('kbi_tanggal,kbi_jns_id,kbi_bea_id'.indexOf(field) >= 0) editable = true;
                else {
                    switch(row.kbi_jns_id) {
                    case 'O':
                        if ('kbi_obt_qty,kbi_sdm_id,kbi_dos_id'.indexOf(field) >= 0) editable = true;
                        break;
                    case 'I':
                        if ('kbi_sdm_id,kbi_dos_id'.indexOf(field) >= 0) editable = true;
                        break;
                    case 'K':
                        if ('kbi_kmr_tgcheckout'.indexOf(field) >= 0) editable = true;
                    }
                }
            }
            if (editable) {
                kbiEdit(index, field);
            }
        },
        onEndEdit:function(index,row) {
            var ed = $(this).datagrid('getEditor', {
                index:index,
                field:'kbi_bea_id'
            });
            row.bea_nama = $(ed.target).combobox('getText');
        }
    });

    function kbiAdd() {
        var today = new Date();
        var selectedKunRow = $('#yan-grid').datagrid('getSelected');
        $('#kbi-grid').datagrid('insertRow', {
            index:0,
            row: {
                kbi_id:0, // mode tambah
                kbi_kun_id:selectedKunRow.kun_id,
                kbi_tanggal:today.toLocaleDateString(),
                kbi_jns_id:'O',
                kun_yan_id:selectedKunRow.kun_yan_id
            }
        });
        $('#kbi-grid').datagrid('selectRow', 0);
        kbiEdit(0, 'kbi_jns_id');
    }

    function kbiEdit(index, fieldName) {
        kbiEditedIdx = index;
        kbiSetEnableDisable();
        $('#kbi-grid').datagrid('beginEdit', kbiEditedIdx);
        var ed = $('#kbi-grid').datagrid('getEditor', {index:kbiEditedIdx, field:fieldName});
        $(ed.target).textbox('textbox').focus();
        $(ed.target).textbox('textbox').select();
    }

    function kbiSave() {
        if (isDemo()) return;
        var rowcheck = $('#kbi-grid').datagrid('getSelected');
        if(rowcheck.kbi_obt_qty>rowcheck.kbi_obtstok){
            $.messager.alert(globalConfig.app_nama, "Jumlah melebihi stok");
            if (rowcheck.kbi_id == 0)
                $('#kbi-grid').datagrid('deleteRow', kbiEditedIdx);
            else
                $('#kbi-grid').datagrid('cancelEdit', kbiEditedIdx);
            kbiEditedIdx = -1;
            kbiSetEnableDisable();
            
        }
        else{
            if ($('#kbi-grid').datagrid('validateRow', kbiEditedIdx)) {
                $('#kbi-grid').datagrid('endEdit', kbiEditedIdx);
                var row = $('#kbi-grid').datagrid('getSelected');
                row.username = globalConfig.login_data.username;
                row.db = getDB();
                $.ajax({
                    type:'POST',
                    data:row,
                    url:getRestAPI('kunbiaya/save'),
                    success:function(retval) {
                        var obj = JSON.parse(retval);
                        if (obj.status == 'success') {
                            $('#kbi-grid').datagrid('updateRow', {
                                index:kbiEditedIdx,
                                row:obj.row
                            });
                            $('#kbi-grid').datagrid('selectRow', kbiEditedIdx);
                        }
                        else
                            $.messager.alert(globalConfig.app_nama, obj.errmsg);
                        kbiEditedIdx = -1;
                        kbiSetEnableDisable();
                    }
                });
            }
        }
    }

    function kbiCancel() {
        var row = $('#kbi-grid').datagrid('getSelected');
        if (row.kbi_id == 0)
            $('#kbi-grid').datagrid('deleteRow', kbiEditedIdx);
        else
            $('#kbi-grid').datagrid('cancelEdit', kbiEditedIdx);
        kbiEditedIdx = -1;
        kbiSetEnableDisable();
    }

    function kbiDelete() {
        var row = $('#kbi-grid').datagrid('getSelected');
        if (row) $.messager.confirm(globalConfig.app_nama,
            'Apakah betul data biaya yang dipilih akan dihapus?', function(r) {
            if (r) $.ajax({
                type:'POST',
                data:{
                    kbi_id:row.kbi_id,
                    db:getDB()
                },
                url:getRestAPI('kunbiaya/delete'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    if (obj.status == 'success') {
                        $('#kbi-grid').datagrid('deleteRow', kbiClickedIdx);
                        kbiClickedIdx = -1;
                    }
                    else
                        $.messager.alert(globalConfig.app_nama, obj.errmsg);
                }
            });
        });
    }

    function kbiSetEnableDisable() {
        if (kbiEditedIdx >= 0) { // mode tambah atau edit
            $('#kbi-btn-add').linkbutton('disable');
            $('#kbi-btn-save').linkbutton('enable');
            $('#kbi-btn-cancel').linkbutton('enable');
            $('#kbi-btn-del').linkbutton('disable');
        }
        else { // mode lihat, tombol hapus akan enable kalau ada row yg terselect
            $('#kbi-btn-add').linkbutton('enable');
            $('#kbi-btn-save').linkbutton('disable');
            $('#kbi-btn-cancel').linkbutton('disable');
            $('#kbi-btn-del').linkbutton('enable');
        }
    }

    function kbiJenisBiayaChange(record) {
        var selectedKunRow = $('#yan-grid').datagrid('getSelected');
        var ed = $('#kbi-grid').datagrid('getEditor', {index:kbiEditedIdx, field:'kbi_bea_id'});
        $(ed.target).combobox('reload',{
            jns_id:record.jns_id,
            com_id:globalConfig.com_id,
            lok_id:globalConfig.lok_id,
            yan_id:selectedKunRow.kun_yan_id,
            lok_jenis:globalConfig.lok_jenis,
            db:getDB()
        });
        if (record.jns_id != 'O' && record.jns_id != 'I') {
            kbiHideEditor('kbi_sdm_id');
            kbiHideEditor('kbi_dos_id');
        }
        if (record.jns_id != 'O')
            kbiHideEditor('kbi_obt_qty');
        if (record.jns_id != 'K')
            kbiHideEditor('kbi_kmr_tgcheckout');
        if (record.jns_id == 'O') {
            ed = $('#kbi-grid').datagrid('getEditor',
                {index:kbiEditedIdx, field:'kbi_dos_id'});
            $(ed.target).combobox('reload',
            getRestAPI('master/dosisobat')); // tidak baca database tapi json di model
        }
        if (record.jns_id == 'I') {
            ed = $('#kbi-grid').datagrid('getEditor',
                {index:kbiEditedIdx, field:'kbi_dos_id'});
            $(ed.target).combobox('reload',
            getRestAPI('master/dosisinjeksi')); // tidak baca database tapi json di model
        }
        if (record.jns_id == 'K') {
            $('#yan-tab').tabs('getTab', 'Evaluasi Dokter').panel('options').tab.show();
            $('#yan-tab').tabs('getTab', 'Evaluasi Perawat').panel('options').tab.show();
        }
    }

    function kbiHideEditor(fieldName) {
        var ed = $('#kbi-grid').datagrid('getEditor', {index:kbiEditedIdx, field:fieldName});
        $(ed.target).parent().hide();
    }

    function kbiCalculateTotal()
    {
        var harga = 0;
        var total = 0;
        var ed = $('#kbi-grid').datagrid('getEditor', {index:kbiEditedIdx, field:'kbi_harga'});
        harga = $(ed.target).numberbox('getValue');
        ed = $('#kbi-grid').datagrid('getEditor', {index:kbiEditedIdx, field:'kbi_jns_id'});
        var jns_id = $(ed.target).combobox('getValue');
        switch(jns_id) {
            case 'D':
            case 'P':
            case 'T':
            case 'L':
            case 'R':
            case 'I':
                total = harga;
                break;
            case 'O':
            case 'K':
                if (jns_id == 'O') {
                    ed = $('#kbi-grid').datagrid('getEditor', {index:kbiEditedIdx, field:'kbi_obt_qty'});
                    var qty = $(ed.target).numberbox('getValue');
                    ed = $('#kbi-grid').datagrid('getEditor', {index:kbiEditedIdx, field:'kbi_obtstok'});
                    var stok = $(ed.target).textbox('getValue');
                    console.log(stok)
                    var stoktarget=stok.split("*");
                    ed = $('#kbi-grid').datagrid('getEditor', {index:kbiEditedIdx, field:'kbi_harga'});
                    harga = $(ed.target).textbox('getValue');
                    if(qty>parseInt(stoktarget[0]))
                    $.messager.alert(globalConfig.app_nama, "Jumlah melebihi stok");
                    total = harga*qty;
                    console.log(harga+"*"+qty+"*"+total)
                }
                else if (jns_id == 'K') {
                    ed = $('#kbi-grid').datagrid('getEditor', {index:kbiEditedIdx, field:'kbi_tanggal'});
                    var from = new Date($(ed.target).datebox('getValue'))
                    ed = $('#kbi-grid').datagrid('getEditor', {index:kbiEditedIdx, field:'kbi_kmr_tgcheckout'});
                    var to = new Date($(ed.target).datebox('getValue'))
                    var d = (to-from)/(24*3600*1000);
                    total = harga*d;
                }
        }
        ed = $('#kbi-grid').datagrid('getEditor', {index:kbiEditedIdx, field:'kbi_total'});
        $(ed.target).numberbox('setValue', total);
        
        return total;
    }

    function kbiObatChange() {
        $('#kbi-obt-dlg').dialog({
            title:'Perubahan Obat Alternatif',
            width:300,
            height:140,
            closable:true,
            border:true,
            modal:true,
            resizable:true,
            maximizable:true,
            href:'layanan/obtchange'
        });
    }
});