// Minus satu artinya mode lihat
// 0 berarti mode tambah
// selain itu berarti sedang ada record yang diedit
var kbioEditedIdx = -1;
var kbioClickedIdx = -1;
var kbioSelectedKunId = null;
var kbioFirstLoad = true;

$(function() {
    $('#kbio-btn-add').linkbutton({
        text:'Tambah Biaya',
        height:24,
        iconCls:'fa fa-plus-circle',
        onClick:function() {kbiAdd();}
    });
    $('#kbio-btn-save').linkbutton({
        text:'Simpan',
        height:24,
        iconCls:'fa fa-check-circle',
        disabled:true,
        onClick:function() {kbiSave();}
    });
    $('#kbio-btn-cancel').linkbutton({
        text:'Batal',
        height:24,
        iconCls:'fa fa-times-circle',
        disabled:true,
        onClick:function() {kbiCancel();}
    });
    $('#kbio-btn-del').linkbutton({
        text:'Hapus',
        height:24,
        onClick:function() {kbiDelete();}
    });
    $('#kbio-search').searchbox({
        prompt:'Ketik kunci pencarian',
        width:200,
        height:24,
        searcher:function(value) {
            alert('Maaf fitur ini belum bisa dipakai');
        }
    });

    $('#kbio-grid').datagrid({
        border:false,
        singleSelect:true,
        pagination:true,
        pageSize:50,
        toolbar:'#kbio-grid-tb',
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
                    url:getRestAPI('master/biaya'),
                    mode:'remote',
                    panelHeight:'auto',
                    panelMaxHeight:200,
                    editable:true,
                    required:false,
                    keyHandler:$.extend({}, $.fn.combobox.defaults.keyHandler, {
                        down:function(e) {
                            $(this).combobox('showPanel');
                            $.fn.combobox.defaults.keyHandler.down.call(this,e);
                        }
                    }),
                    onLoadSuccess:function() {
                        var row = $('#kbio-grid').datagrid('getSelected');
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
                    },
                    onSelect:function(record) {
                        var ed = $('#kbio-grid').datagrid('getEditor', {index:kbioEditedIdx, field:'kbi_harga'});
                        $(ed.target).numberbox('setValue', record.bea_harga);
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
                        db:globalConfig.login_data.db,
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
                    url:getRestAPI('master/dosisobat'),
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
            db:globalConfig.login_data.db,
            man_id: globalConfig.ids.man_id,
            hwn_id: globalConfig.ids.hwn_id
        },
        url:getRestAPI('kunbiaya/read'),
        onSelect:function(index,row){
            if(row.kbi_jns_id=='K' && 
                (globalConfig.login_data.kamaronly!=1 || 
                globalConfig.login_data.kamaronly==undefined)){
                $('#yan-tab').tabs('getTab', 'Evaluasi Dokter').panel('options').tab.show();
                $('#yan-tab').tabs('getTab', 'Evaluasi Perawat').panel('options').tab.show();
            }
            else{
                $('#yan-tab').tabs('getTab', 'Evaluasi Dokter').panel('options').tab.hide();
                $('#yan-tab').tabs('getTab', 'Evaluasi Perawat').panel('options').tab.hide();
            }
        },
        onLoadSuccess:function(data) {
            console.log("on load success");
            console.log("success = "+JSON.stringify(data.rows));
            console.log("man_id = "+globalConfig.ids.man_id);
            if (data.rows.length > 0) {
                $(this).datagrid('selectRow', 0);
                kbioClickedIdx = 0;
            }
            // if (kbioFirstLoad) {
            //     kbioFirstLoad = false;
            //     $('#yan-grid').datagrid('selectRow', 0);
            // }
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
            if (kbioClickedIdx == -1)
                kbioClickedIdx = index;
            else if (kbioClickedIdx != index) {
                if (kbioEditedIdx != -1) kbiCancel();
                kbioClickedIdx = index;
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
        $('#kbio-grid').datagrid('insertRow', {
            index:0,
            row: {
                kbi_id:0, // mode tambah
                kbi_kun_id:selectedKunRow.kun_id,
                kbi_tanggal:today.toLocaleDateString(),
                kbi_jns_id:'O',
                kun_yan_id:selectedKunRow.kun_yan_id
            }
        });
        $('#kbio-grid').datagrid('selectRow', 0);
        kbiEdit(0, 'kbi_jns_id');
    }

    function kbiEdit(index, fieldName) {
        kbioEditedIdx = index;
        kbiSetEnableDisable();
        $('#kbio-grid').datagrid('beginEdit', kbioEditedIdx);
        var ed = $('#kbio-grid').datagrid('getEditor', {index:kbioEditedIdx, field:fieldName});
        $(ed.target).textbox('textbox').focus();
        $(ed.target).textbox('textbox').select();
    }

    function kbiSave() {
        if ($('#kbio-grid').datagrid('validateRow', kbioEditedIdx)) {
            $('#kbio-grid').datagrid('endEdit', kbioEditedIdx);
            var row = $('#kbio-grid').datagrid('getSelected');
            row.username = globalConfig.login_data.username;
            row.db = globalConfig.login_data.db;
            $.ajax({
                type:'POST',
                data:row,
                url:getRestAPI('kunbiaya/save'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    if (obj.status == 'success') {
                        $('#kbio-grid').datagrid('updateRow', {
                            index:kbioEditedIdx,
                            row:obj.row
                        });
                        $('#kbio-grid').datagrid('selectRow', kbioEditedIdx);
                    }
                    else
                        $.messager.alert(globalConfig.app_nama, obj.errmsg);
                    kbioEditedIdx = -1;
                    kbiSetEnableDisable();
                }
            });
        }
    }

    function kbiCancel() {
        var row = $('#kbio-grid').datagrid('getSelected');
        if (row.kbi_id == 0)
            $('#kbio-grid').datagrid('deleteRow', kbioEditedIdx);
        else
            $('#kbio-grid').datagrid('cancelEdit', kbioEditedIdx);
        kbioEditedIdx = -1;
        kbiSetEnableDisable();
    }

    function kbiDelete() {
        var row = $('#kbio-grid').datagrid('getSelected');
        if (row) $.messager.confirm(globalConfig.app_nama,
            'Apakah betul data biaya yang dipilih akan dihapus?', function(r) {
            if (r) $.ajax({
                type:'POST',
                data:{kbi_id:row.kbi_id},
                url:getRestAPI('kunbiaya/delete'),
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    if (obj.status == 'success') {
                        $('#kbio-grid').datagrid('deleteRow', kbioClickedIdx);
                        kbioClickedIdx = -1;
                    }
                    else
                        $.messager.alert(globalConfig.app_nama, obj.errmsg);
                }
            });
        });
    }

    function kbiSetEnableDisable() {
        if (kbioEditedIdx >= 0) { // mode tambah atau edit
            $('#kbio-btn-add').linkbutton('disable');
            $('#kbio-btn-save').linkbutton('enable');
            $('#kbio-btn-cancel').linkbutton('enable');
            $('#kbio-btn-del').linkbutton('disable');
        }
        else { // mode lihat, tombol hapus akan enable kalau ada row yg terselect
            $('#kbio-btn-add').linkbutton('enable');
            $('#kbio-btn-save').linkbutton('disable');
            $('#kbio-btn-cancel').linkbutton('disable');
            $('#kbio-btn-del').linkbutton('enable');
        }
    }

    function kbiJenisBiayaChange(record) {
        var selectedKunRow = $('#yan-grid').datagrid('getSelected');
        var ed = $('#kbio-grid').datagrid('getEditor', {index:kbioEditedIdx, field:'kbi_bea_id'});
        $(ed.target).combobox('reload',{
            jns_id:record.jns_id,
            com_id:globalConfig.com_id,
            lok_id:globalConfig.lok_id,
            yan_id:selectedKunRow.kun_yan_id
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
            ed = $('#kbio-grid').datagrid('getEditor',
                {index:kbioEditedIdx, field:'kbi_dos_id'});
            $(ed.target).combobox('reload', getRestAPI('master/dosisobat'));
        }
        if (record.jns_id == 'I') {
            ed = $('#kbio-grid').datagrid('getEditor',
                {index:kbioEditedIdx, field:'kbi_dos_id'});
            $(ed.target).combobox('reload', getRestAPI('master/dosisinjeksi'));
        }
        if (record.jns_id == 'K') {
            $('#yan-tab').tabs('getTab', 'Evaluasi Dokter').panel('options').tab.show();
            $('#yan-tab').tabs('getTab', 'Evaluasi Perawat').panel('options').tab.show();
        }
    }

    function kbiHideEditor(fieldName) {
        var ed = $('#kbio-grid').datagrid('getEditor', {index:kbioEditedIdx, field:fieldName});
        $(ed.target).parent().hide();
    }

    function kbiCalculateTotal()
    {
        var harga = 0;
        var total = 0;
   
        var ed = $('#kbio-grid').datagrid('getEditor', {index:kbioEditedIdx, field:'kbi_harga'});
        harga = $(ed.target).numberbox('getValue');
        ed = $('#kbio-grid').datagrid('getEditor', {index:kbioEditedIdx, field:'kbi_jns_id'});
        var jns_id = $(ed.target).combobox('getValue');
        switch(jns_id) {
            case 'D':
            case 'P':
            case 'T':
            case 'L':
            case 'I':
                total = harga;
                break;
            case 'O':
            case 'K':
                if (jns_id == 'O') {
                    ed = $('#kbio-grid').datagrid('getEditor', {index:kbioEditedIdx, field:'kbi_obt_qty'});
                    var qty = $(ed.target).numberbox('getValue');
                    total = harga*qty;
                }
                else if (jns_id == 'K') {
                    ed = $('#kbio-grid').datagrid('getEditor', {index:kbioEditedIdx, field:'kbi_tanggal'});
                    var from = new Date($(ed.target).datebox('getValue'))
                    ed = $('#kbio-grid').datagrid('getEditor', {index:kbioEditedIdx, field:'kbi_kmr_tgcheckout'});
                    var to = new Date($(ed.target).datebox('getValue'))
                    var d = (to-from)/(24*3600*1000);
                    total = harga*d;
                }
        }
        ed = $('#kbio-grid').datagrid('getEditor', {index:kbioEditedIdx, field:'kbi_total'});
        $(ed.target).numberbox('setValue', total);
   
        return total;
    }
});