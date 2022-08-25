// Minus satu artinya mode lihat
// 0 berarti mode tambah
// selain itu berarti sedang ada record yang diedit
var hwnEditedId = -1;

// Ini untuk menandai apakah event onChange pada textbox dan combobox
// ditrigger oleh code atau oleh user. Defaultnya oleh user
var hwnChangeByUser = true;
var hwnControlHeight = 24;

$(function() {
    $('#hwn-btn-add').linkbutton({
        onClick:function() {hwnAdd();}
    });
    $('#hwn-btn-save').linkbutton({
        disabled:true,
        onClick:function() {hwnSave();}
    });
    $('#hwn-btn-cancel').linkbutton({
        disabled:true,
        onClick:function() {hwnCancel();}
    });
    $('#hwn-btn-del').linkbutton({
        disabled:true,
        onClick:function() {hwnDelete();}
    });
    /*$('#hwn-search').searchbox({
        prompt:'Ketik nama untuk pencarian',
        width:200,
        searcher:function(value) {
            alert('Maaf fitur ini belum bisa dipakai');
        }
    });*/
    $('#hwn-form-id').textbox({
        width:80,
        prompt:'auto',
        height:hwnControlHeight,
        readonly:true
    });
    $('#hwn-form-norm').textbox({
        width:120,
        prompt:'auto',
        height:hwnControlHeight,
        readonly:true
    });
    $('#hwn-form-hewan').textbox({
        width:150,
        height:hwnControlHeight,
        onChange:function() {hwnTextboxOnChange(this)},
        inputEvents:textboxInputEvents('#hwn-form-manusia')
    });
    $('#hwn-form-manusia').combobox({
        width:200,
        prompt:'Ketik minimal 3 huruf untuk mencari',
        valueField:'man_id',
        textField:'man_nama',
        panelHeight:'auto',
        panelMaxHeight:200,
        height:hwnControlHeight,
        mode:'remote',
        method:'post',
        queryParams:{
            db:getDB(),
            man_com_id:globalConfig.com_id
        },
        url:getRestAPI('manusia/search'),
        onChange:function() {hwnSetEdited();},
        keyHandler:comboboxKeyHandler('#hwn-form-tglahir')
    });
    $('#hwn-form-tglahir').datebox({
        width:120,
        editable:false,
        height:hwnControlHeight,
        onChange:function() {hwnTextboxOnChange(this)},
        inputEvents:textboxInputEvents('#hwn-form-kelamin')
    });
    $('#hwn-form-kelamin').combobox({
        width:120,
        valueField:'kel_id',
        textField:'kel_nama',
        editable:false,
        panelHeight:'auto',
        height:hwnControlHeight,
        url:getRestAPI('master/kelaminhewan'), // tidak membaca database tapi json di model
        onChange:function() {hwnSetEdited();},
        keyHandler:comboboxKeyHandler('#hwn-form-spesies')
    });
    $('#hwn-form-spesies').combobox({
        width:120,
        valueField:'spc_id',
        textField:'spc_nama',
        editable:false,
        height:hwnControlHeight,
        panelHeight:'auto',
        panelMaxHeight:200,
        url:getRestAPI('master/spesies'), // tidak membaca database tapi json di model
        onChange:function() {hwnSetEdited();},
        keyHandler:comboboxKeyHandler('#hwn-form-spesies')
    });
    $('#hwn-form-ras').combobox({
        width:120,
        valueField:'ras_id',
        textField:'ras_nama',
        editable:false,
        height:hwnControlHeight,
        panelHeight:'auto',
        panelMaxHeight:200,
        url:getRestAPI('master/ras'), // tidak membaca database tapi json di model
        onChange:function() {hwnSetEdited();},
        keyHandler:comboboxKeyHandler('#hwn-form-hewan')
    });
    $('#hwn-grid').datagrid({
        border:false,
        pagination:true,
        pageSize:50,
        toolbar:'#hwn-grid-tb',
        fit:true,
        singleSelect:true,
        rowStyler:function(index,row) {
            if (!row.available){
                return 'color:red';
            }
        },
        columns:[[{
            field:'hwn_id',
            title:'ID',
            resizable:false,
            hidden:true,
            width:35
        },{
            field:'hwn_norm',
            title:'No. RM',
            resizable:false,
            width:120
        },{
            field:'hwn_nama',
            title:'Nama hewan',
            resizable:false,
            width:150
        },{
            field:'hwn_man_id',
            title:'Nama pemilik',
            resizable:false,
            formatter:function(value, row) {return row.man_nama},
            width:230
        },{
            field:'hwn_tglahir',
            title:'Tgl lahir',
            resizable:false,
            width:120
        },{
            field:'hwn_kelamin',
            title:'Kelamin',
            resizable:false,
            width:60
        },{
            field:'hwn_spesies',
            title:'Spesies',
            resizable:false,
            width:120
        },{
            field:'hwn_ras',
            title:'Spesies',
            resizable:false,
            width:120
        }]],
        queryParams:{
            db:getDB(),
            man_com_id:globalConfig.com_id
        },
        url:getRestAPI('hewan/read'),
        onLoadSuccess:function(data) {
            if (data.rows.length > 0) $(this).datagrid('selectRow', 0);
            $('#hwn-form-hewan').textbox('textbox').attr('maxlength', 15);
        },
        onSelect:function(index, row) {
            hwnChangeByUser = false;
            $('#hwn-form-id').textbox('setValue', row.hwn_id);
            $('#hwn-form-norm').textbox('setValue', row.hwn_norm);
            $('#hwn-form-hewan').textbox('setValue', row.hwn_nama);
            $('#hwn-form-manusia').combobox('loadData',[{man_id:row.hwn_man_id, man_nama:row.man_nama}]);
            $('#hwn-form-manusia').combobox('setValue', row.hwn_man_id);
            $('#hwn-form-tglahir').datebox('setValue', row.hwn_tglahir);
            $('#hwn-form-kelamin').combobox('setValue', row.hwn_kelamin);
            $('#hwn-form-spesies').combobox('setValue', row.hwn_spesies);
            $('#hwn-form-ras').combobox('setValue', row.hwn_ras);
            hwnChangeByUser = true;
            $('#hwn-btn-del').linkbutton('enable');
            $('#hwn-form-hewan').textbox('textbox').focus();
        }
    });

    function hwnAdd() {
        hwnChangeByUser = false;
        var today = new Date();
        $('#hwn-form-id').textbox('setValue', '');
        $('#hwn-form-norm').textbox('setValue', '');
        $('#hwn-form-hewan').textbox('setValue', '');
        $('#hwn-form-manusia').combobox('setValue', '');
        $('#hwn-form-tglahir').datebox('setValue', today.toLocaleDateString());
        $('#hwn-form-kelamin').combobox('setValue', 'J');
        $('#hwn-form-spesies').combobox('setValue', 'Domcat');
        $('#hwn-form-ras').combobox('setValue', 'Lokal');
        hwnChangeByUser = true;
        hwnEditedId = 0; // mode tambah
        hwnSetEnableDisable();
        $('#hwn-form-hewan').textbox('textbox').focus();
    }

    function hwnSave() {
        var data = {
            hwn_id:hwnEditedId,
            hwn_norm:$('#hwn-form-norm').textbox('getValue'),
            hwn_nama:$('#hwn-form-hewan').textbox('getValue'),
            hwn_man_id:$('#hwn-form-manusia').combobox('getValue'),
            hwn_tglahir:$('#hwn-form-tglahir').datebox('getValue'),
            hwn_kelamin:$('#hwn-form-kelamin').combobox('getValue'),
            hwn_spesies:$('#hwn-form-spesies').combobox('getValue'),
            hwn_ras:$('#hwn-form-ras').combobox('getValue'),
            db:getDB(),
            hwn_com_id:globalConfig.com_id
        }
        $.ajax({
            type:'POST',
            data:data,
            url:getRestAPI('hewan/save'),
            success:function(retval) {
                var obj = JSON.parse(retval);
                if (obj.status == 'success') {
                    if (hwnEditedId == 0) {
                        $('#hwn-grid').datagrid('insertRow', {
                            index:0,
                            row:obj.row
                        });
                        $('#hwn-grid').datagrid('selectRow', 0);
                    }
                    else {
                        var selectedRow = $('#hwn-grid').datagrid('getSelected');
                        var index = $('#hwn-grid').datagrid('getRowIndex', selectedRow);
                        $('#hwn-grid').datagrid('updateRow', {
                            index:index,
                            row:obj.row
                        });
                        $('#hwn-grid').datagrid('selectRow', index);
                    }
                }
                else
                    $.messager.alert(globalConfig.app_nama, obj.errmsg);
                hwnEditedId = -1;
                hwnSetEnableDisable();
            }
        });
    }

    function hwnCancel() {
        hwnEditedId = -1;
        hwnSetEnableDisable();
        var row = $('#hwn-grid').datagrid('getSelected');
        if (row)
            $('#hwn-grid').datagrid('selectRow', $('#hwn-grid').datagrid('getRowIndex', row));
    }

    function hwnDelete() {
        alert('Maaf menu ini belum bisa dipakai');
    }

    function hwnSetEdited() {
        if (hwnEditedId == -1 && hwnChangeByUser) {
            hwnEditedId = $('#hwn-form-id').textbox('getValue');
            hwnSetEnableDisable();
        }
    }

    function hwnSetEnableDisable() {
        if (hwnEditedId >= 0) { // mode tambah atau edit
            $('#hwn-btn-add').linkbutton('disable');
            $('#hwn-btn-save').linkbutton('enable');
            $('#hwn-btn-cancel').linkbutton('enable');
            $('#hwn-btn-del').linkbutton('disable');
        }
        else { // mode lihat, tombol hapus akan enable kalau ada row yg terselect
            $('#hwn-btn-add').linkbutton('enable');
            $('#hwn-btn-save').linkbutton('disable');
            $('#hwn-btn-cancel').linkbutton('disable');
            $('#hwn-btn-del').linkbutton('disable');
        }
    }

    function hwnTextboxOnChange(obj) {
        if (!hwnChangeByUser) return;
        var str = $(obj).textbox('getValue');
        str = setCapitalSentenceEveryWord(str);
        hwnChangeByUser = false;
        $(obj).textbox('setValue', str);
        hwnChangeByUser = true;
        hwnSetEdited();
    }
});