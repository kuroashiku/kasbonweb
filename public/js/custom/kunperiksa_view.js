// Minus satu artinya mode lihat
// 0 berarti mode tambah
// selain itu berarti sedang ada record yang diedit
var kprEditedId = -1;

// Ini untuk menandai apakah event onChange pada textbox dan combobox
// ditrigger oleh code atau oleh user. Defaultnya oleh user
var kprChangeByUser = true;
var kprControlHeight = 24;
var kprControlWidth = (globalConfig.login_data.rsa==1 || globalConfig.login_data.rsa!=undefined) ? 150 : 120;

$(function() {
    $('#kpr-btn-add').linkbutton({
        text:'Tambah',
        height:24,
        iconCls:'fa fa-plus-circle',
        onClick:function() {kprAdd();}
    });
    $('#kpr-btn-save').linkbutton({
        text:'Simpan',
        height:24,
        iconCls:'fa fa-check-circle',
        disabled:true,
        onClick:function() {kprSave();}
    });
    $('#kpr-btn-cancel').linkbutton({
        text:'Batal',
        height:24,
        iconCls:'fa fa-times-circle',
        disabled:true,
        onClick:function() {kprCancel();}
    });
    $('#kpr-btn-del').linkbutton({
        text:'Hapus',
        height:24,
        disabled:true,
        onClick:function() {kprDelete();}
    });
    $('#kpr-btn-del').hide();
    $('#kpr-form-id').textbox({
        width:220,
        label:'ID',
        labelWidth:kprControlWidth,
        prompt:'auto',
        height:kprControlHeight,
        readonly:true
    });
    $('#kpr-form-tgperiksa').datebox({
        editable:false,
        height:kprControlHeight,
        onChange:function() {kprTextboxOnChange(this)},
        inputEvents:textboxInputEvents('#kpr-form-anamnesa')
    });
    $('#kpr-form-anamnesa').textbox({
        width:'90%',
        height:80,
        label:'Anamnesa',
        labelWidth:kprControlWidth,
        multiline:true,
        onChange:function() {kprSetEdited();}
    });
    $('#kpr-form-diagnosa').textbox({
        width:'100%',
        height:60,
        label:'Diagnosis',
        labelWidth:kprControlWidth,
        multiline:true,
        onChange:function() {kprSetEdited();}
    });
    $('#kpr-form-kebidanan').textbox({
        width:'100%',
        height:60,
        label:'Pemeriksaan Kebidanan',
        labelWidth:kprControlWidth,
        multiline:true,
        onChange:function() {kprSetEdited();}
    });
    $('#kpr-form-tb').numberbox({
        width:kprControlWidth + 40,
        label:'TB/BB',
        min:0,
        max:250,
        labelWidth:kprControlWidth,
        onChange:function() {kprSetEdited();},
        height:kprControlHeight
    });
    $('#kpr-form-bb').numberbox({
        width:40,
        min:0,
        max:500,
        onChange:function() {kprSetEdited();},
        height:kprControlHeight
    });
    $('#kpr-form-nafas').numberbox({
        width:kprControlWidth + 40,
        min:0,
        max:200,
        label:'Nafas',
        labelWidth:kprControlWidth,
        onChange:function() {kprSetEdited();},
        height:kprControlHeight
    });
    $('#kpr-form-sistolik').numberbox({
        width:40,
        min:0,
        max:200,
        onChange:function() {kprSetEdited();},
        height:kprControlHeight
    });
    $('#kpr-form-diastolik').numberbox({
        width:40,
        min:0,
        max:200,
        onChange:function() {kprSetEdited();},
        height:kprControlHeight
    });
    $('#kpr-form-suhu').numberbox({
        width:50,
        min:0,
        max:50,
        precision:1,
        decimalSeparator:'.',
        onChange:function() {kprSetEdited();},
        height:kprControlHeight
    });
    $('#kpr-form-saturasi').numberbox({
        width:kprControlWidth + 40,
        min:0,
        max:200,
        label:'Saturasi Oksigen',
        labelWidth:kprControlWidth,
        onChange:function() {kprSetEdited();},
        height:kprControlHeight
    });
    $('#kpr-form-penyakit').combobox({
        width:'100%',
        label:'Diagnosis (ICD X)',
        labelWidth:kprControlWidth,
        height:kprControlHeight,
        prompt:'Ketik minimal 3 huruf untuk mencari',
        valueField:'kit_id',
        textField:'kit_nama',
        panelHeight:'auto',
        panelMaxHeight:200,
        mode:'remote',
        method:'POST',
        queryParams:{db:getDB()},
        url:getRestAPI('penyakit/search'),
        onChange:function() {kprSetEdited();},
        keyHandler:comboboxKeyHandler('#kpr-form-prognosa')
    });
    $('#kpr-form-prognosa').textbox({
        width:'100%',
        height:60,
        label:'Prognosa',
        labelWidth:kprControlWidth,
        multiline:true,
        onChange:function() {kprSetEdited();},
    });
    $('#kpr-form-terapi').textbox({
        width:'100%',
        height:60,
        label:'Terapi',
        labelWidth:kprControlWidth,
        multiline:true,
        onChange:function() {kprSetEdited();},
    });
    $('#kpr-btn-detail').linkbutton({
        text:'Isian Khusus Klinik',
        height:24,
        iconCls:'fa fa-book-medical',
        disabled: true,
        onClick:function() {kprPoliKhusus();}
    });
    if (globalConfig.lok_jenis == 2) {
        $('#kpr-btn-detail').parent().hide();
    }
    $('#kpr-grid').datagrid({
        border:false,
        fit:true,
        toolbar:'#kpr-grid-tb',
        singleSelect:true,
        columns:[[{
            field:'kun_noregistrasi',
            title:'No. registrasi',
            resizable:false,
            width:100
        },{
            field:'kpr_tgperiksa',
            title:'Tgl Periksa',
            resizable:false,
            width:95
        },{
            field:'kpr_id',
            title:'ID',
            resizable:false,
            width:100
        }]],
        queryParams:{db:getDB()},
        url:getRestAPI('kunperiksa/read'),
        onLoadSuccess:function(data) {
            if (data.rows.length > 0) $(this).datagrid('selectRow', 0);
            else kprClearForm();
            if(globalConfig.login_data.rsa == 1 && globalConfig.login_data.rsa != undefined){
                $('#kpr-form-diagnosa').textbox('label').html('Pemeriksaan Fisik');
                $('#kpr-form-prognosa').textbox('label').html('Pemeriksaan Penunjang');
            }
            $('#kpr-form-anamnesa').textbox('textbox').attr('maxlength', 500);
            $('#kpr-form-diagnosa').textbox('textbox').attr('maxlength', 500);
            $('#kpr-form-kebidanan').textbox('textbox').attr('maxlength', 500);
            $('#kpr-form-prognosa').textbox('textbox').attr('maxlength', 500);
            $('#kpr-form-terapi').textbox('textbox').attr('maxlength', 500);
            kprSetReadonly(true);
        },
        onSelect:function(index, row) {
            kprChangeByUser = false;
            $('#kpr-form-id').textbox('setValue', row.kpr_id);
            kprSetReadonly(false);
            $('#kpr-form-tgperiksa').datebox('setValue', row.kpr_tgperiksa);
            $('#kpr-form-anamnesa').textbox('setValue', row.kpr_anamnesa);
            $('#kpr-form-diagnosa').textbox('setValue', row.kpr_diagnosa);
            $('#kpr-form-kebidanan').textbox('setValue', row.kpr_kebidanan);
            $('#kpr-form-penyakit').combobox('loadData',[{kit_id:row.kpr_kit_id, kit_nama:row.kit_nama}]);
            $('#kpr-form-penyakit').combobox('setValue', row.kpr_kit_id);
            $('#kpr-form-prognosa').textbox('setValue', row.kpr_prognosa);
            $('#kpr-form-terapi').textbox('setValue', row.kpr_terapi);
            $('#kpr-form-tb').numberbox('setValue', row.kpr_tb);
            $('#kpr-form-bb').numberbox('setValue', row.kpr_bb);
            $('#kpr-form-nafas').numberbox('setValue', row.kpr_nafas);
            $('#kpr-form-sistolik').numberbox('setValue', row.kpr_sistolik);
            $('#kpr-form-diastolik').numberbox('setValue', row.kpr_diastolik);
            $('#kpr-form-suhu').numberbox('setValue', row.kpr_suhu);
            $('#kpr-form-saturasi').numberbox('setValue', row.kpr_saturasi);
            kprChangeByUser = true;
            $('#kpr-form-anamnesa').textbox('textbox').focus();
        }
    });

    function kprSetReadonly(readonly) {
        $('#kpr-form-tgperiksa').datebox('readonly', readonly);
        $('#kpr-form-anamnesa').textbox('readonly', readonly);
        $('#kpr-form-diagnosa').textbox('readonly', readonly);
        $('#kpr-form-kebidanan').textbox('readonly', readonly);
        $('#kpr-form-penyakit').combobox('readonly', readonly);
        $('#kpr-form-prognosa').textbox('readonly', readonly);
        $('#kpr-form-terapi').textbox('readonly', readonly);
        $('#kpr-form-tb').numberbox('readonly', readonly);
        $('#kpr-form-bb').numberbox('readonly', readonly);
        $('#kpr-form-nafas').numberbox('readonly', readonly);
        $('#kpr-form-sistolik').numberbox('readonly', readonly);
        $('#kpr-form-diastolik').numberbox('readonly', readonly);
        $('#kpr-form-suhu').numberbox('readonly', readonly);
        $('#kpr-form-saturasi').numberbox('readonly', readonly);
    }

    function kprSetEdited() {
        if (kprEditedId == -1 && kprChangeByUser) {
            kprEditedId = $('#kpr-form-id').textbox('getValue');
            kprSetEnableDisable();
        }
    }

    function kprAdd() {
        kprChangeByUser = false;
        kprClearForm();
        var today = new Date();
        $('#kpr-form-tgperiksa').datebox('setValue', today.toLocaleDateString());
        kprSetReadonly(false);
        kprChangeByUser = true;
        kprEditedId = 0; // mode tambah
        kprSetEnableDisable();
        $('#kpr-form-anamnesa').textbox('textbox').focus();
        $('#kpr-grid').datagrid('unselectAll');
    }

    function kprSave() {
        if (isDemo()) return;
        var selectedKunRow = $('#yan-grid').datagrid('getSelected');
        var data = {
            kpr_id:kprEditedId,
            kpr_tgperiksa:$('#kpr-form-tgperiksa').datebox('getValue'),
            kpr_kun_id:selectedKunRow.kun_id,
            kun_yan_id:selectedKunRow.kun_yan_id,
            kpr_anamnesa:$('#kpr-form-anamnesa').textbox('getValue'),
            kpr_diagnosa:$('#kpr-form-diagnosa').textbox('getValue'),
            kpr_kebidanan:$('#kpr-form-kebidanan').textbox('getValue'),
            kpr_kit_id:$('#kpr-form-penyakit').combobox('getValue'),
            kpr_prognosa:$('#kpr-form-prognosa').textbox('getValue'),
            kpr_terapi:$('#kpr-form-terapi').textbox('getValue'),
            kpr_tb:$('#kpr-form-tb').numberbox('getValue'),
            kpr_bb:$('#kpr-form-bb').numberbox('getValue'),
            kpr_nafas:$('#kpr-form-nafas').numberbox('getValue'),
            kpr_sistolik:$('#kpr-form-sistolik').numberbox('getValue'),
            kpr_diastolik:$('#kpr-form-diastolik').numberbox('getValue'),
            kpr_suhu:$('#kpr-form-suhu').numberbox('getValue'),
            kpr_saturasi:$('#kpr-form-saturasi').numberbox('getValue'),
            //edited by naufal
            username:globalConfig.login_data.username,
            db:getDB()
        };
        $.ajax({
            type:'POST',
            data:data,
            url:getRestAPI('kunperiksa/save'),
            success:function(retval) {
                var obj = JSON.parse(retval);
                if (obj.status == 'success') {
                    if (kprEditedId == 0) {
                        $('#kpr-grid').datagrid('insertRow', {
                            index:0,
                            row:obj.row
                        });
                        $('#kpr-grid').datagrid('selectRow', 0);
                    }
                    else {
                        var selectedRow = $('#kpr-grid').datagrid('getSelected');
                        var index = $('#kpr-grid').datagrid('getRowIndex', selectedRow);
                        $('#kpr-grid').datagrid('updateRow', {
                            index:index,
                            row:obj.row
                        });
                        $('#kpr-grid').datagrid('selectRow', index);
                    }
                }
                else
                    $.messager.alert(globalConfig.app_nama, obj.errmsg);
                kprEditedId = -1;
                kprSetEnableDisable();
            }
        });
    }

    function kprCancel() {
        kprEditedId = -1;
        kprSetEnableDisable();
        var row = $('#kpr-grid').datagrid('getSelected');
        if (row)
            $('#kpr-grid').datagrid('selectRow', $('#kpr-grid').datagrid('getRowIndex', row));
        else
            kprSetReadonly(true);
    }

    function kprDelete() {
        $.messager.alert(globalConfig.app_nama, 'Maaf menu ini belum bisa dipakai');
    }

    function kprSetEnableDisable() {
        if (kprEditedId >= 0) { // mode tambah atau edit
            $('#kpr-btn-add').linkbutton('disable');
            $('#kpr-btn-save').linkbutton('enable');
            $('#kpr-btn-cancel').linkbutton('enable');
            $('#kpr-btn-del').linkbutton('disable');
        }
        else { // mode lihat, tombol hapus akan enable kalau ada row yg terselect
            $('#kpr-btn-add').linkbutton('enable');
            $('#kpr-btn-save').linkbutton('disable');
            $('#kpr-btn-cancel').linkbutton('disable');
            $('#kpr-btn-del').linkbutton('enable');
        }
    }

    function kprTextboxOnChange(obj) {
        if (!kprChangeByUser) return;
        var str = $(obj).textbox('getValue');
        str = setCapitalSentenceEveryWord(str);
        var prev = kprChangeByUser;
        kprChangeByUser = false;
        $(obj).textbox('setValue', str);
        kprChangeByUser = prev;
        kprSetEdited();
    }

    function kprClearForm() {
        var prev = kprChangeByUser;
        kprChangeByUser = false;
        $('#kpr-form-id').textbox('setValue', '');
        $('#kpr-form-tgperiksa').datebox('setValue', '');
        $('#kpr-form-anamnesa').textbox('setValue', '');
        $('#kpr-form-diagnosa').textbox('setValue', '');
        $('#kpr-form-kebidanan').textbox('setValue', '');
        $('#kpr-form-penyakit').combobox('setValue', '');
        $('#kpr-form-prognosa').textbox('setValue', '');
        $('#kpr-form-terapi').textbox('setValue', '');
        $('#kpr-form-tb').numberbox('setValue', '');
        $('#kpr-form-bb').numberbox('setValue', '');
        $('#kpr-form-nafas').numberbox('setValue', '');
        $('#kpr-form-sistolik').numberbox('setValue', '');
        $('#kpr-form-diastolik').numberbox('setValue', '');
        $('#kpr-form-suhu').numberbox('setValue', '');
        $('#kpr-form-saturasi').numberbox('setValue', '');
        kprChangeByUser = prev;
    }

    function kprPoliKhusus() {
        switch(globalConfig.layanan_dipilih){
            case 'OBG':
                $('#kpr-obgyn-dlg').dialog({
                    title:'Isian Klinik Kandungan',
                    width:1000,
                    height:600,
                    closable:true,
                    border:true,
                    modal:true,
                    resizable:true,
                    maximizable:true,
                    href:'kunperiksa/obgyn'
                });
                break;
            case 'GIG':
                $('#kpr-dental-dlg').dialog({
                    title:'Isian Poli Gigi',
                    width:1000,
                    height:600,
                    closable:true,
                    border:true,
                    modal:true,
                    resizable:true,
                    maximizable:true,
                    href:'kunperiksa/dental'
                });
                break;
            default:
                return;
        }
    }
});