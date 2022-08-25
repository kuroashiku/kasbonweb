// Minus satu artinya mode lihat
// 0 berarti mode tambah
// selain itu berarti sedang ada record yang diedit
var lktEditedId = -1;
var lktIndexNow;
// Ini untuk menandai apakah event onChange pada textbox dan combobox
// ditrigger oleh code atau oleh user. Defaultnya oleh user
var lktChangeByUser = true;
var lktControlHeight = 24;
var lktRujukan;
var lktman = -1;
$(function() {
    $('#lkt-btn-add').linkbutton({
        iconCls:'fa fa-plus-circle fa-lg',
        text:'Tambah',
        height:24,
        onClick:function() {lktAdd();}
    });
    $('#lkt-btn-save').linkbutton({
        iconCls:'fa fa-check-circle fa-lg',
        text:'Simpan',
        height:24,
        disabled:true,
        onClick:function() {lktSave();}
    });
    $('#lkt-btn-cancel').linkbutton({
        iconCls:'fa fa-times-circle fa-lg',
        text:'Batal',
        height:24,
        disabled:true,
        onClick:function() {lktCancel();}
    });
    $('#lkt-btn-del').linkbutton({
        disabled:true,
        text:'Hapus',
        height:24,
        onClick:function() {lktDelete();}
    });
    $('#lkt-btn-del').hide();
    $('#lkt-btn-idpas').linkbutton({
        text:'Cetak identitas pasien',
        iconCls:'fa fa-file fa-lg',
        height:24,
        onClick:function() {lktRepIdPas();}
    });
    $('#lkt-btn-man').linkbutton({
        iconCls:'fa fa-plus-circle fa-lg',
        text:'Tambah Pasien',
        height:24,
        onClick:function() {
            if ($('#main-tab').tabs('getTab', 'Pasien')) {
                $('#main-tab').tabs('select', 'Pasien');
                lktAddPasien();
            }
            else $.messager.alert(globalConfig.app_nama,
                "Fitur ini bisa dipakai kalau modul Pasien sudah dibuka");
        }
    });
    $('#lkt-btn-rekap').linkbutton({
        text:'Rekap',
        iconCls:'fa fa-file fa-lg',
        height:24,
        onClick:function() {lktRekap();}
    });

    if (globalConfig.lok_jenis == 2) { // SiReDisH
        $('#lkt-btn-idpas').hide();
        $('#lkt-btn-man').hide();
        $('#lkt-btn-rekap').hide();
    }

    $('#lkt-search').searchbox({
        prompt:'Ketik nama untuk pencarian',
        width:200,
        height:lktControlHeight,
        searcher:function(value) {
            var checked = $('#lkt-chk-selesai').checkbox('options').checked;
            $('#lkt-grid').datagrid('reload', {
                kun_status:checked?'SELESAI':'SEMUA',
                key_val:value,
                lok_id:globalConfig.login_data?globalConfig.login_data.lok_id:null,
                db:getDB()
            });
        }
    });
    $('#lkt-chk-selesai').checkbox({
        label:'Tampilkan yang SELESAI saja',
        labelPosition:'after',
        labelWidth:200,
        checked:false,
        onChange:function(checked) {
            var nama = $('#lkt-search').searchbox('getValue');
            $('#lkt-grid').datagrid('reload', {
                key_val:nama,
                kun_status:checked?'SELESAI':'SEMUA',
                lok_id:globalConfig.login_data?globalConfig.login_data.lok_id:null,
                db:getDB()
            });
        }
    });
    $('#lkt-form-id').textbox({
        width:100,
        prompt:'auto',
        height:lktControlHeight,
        readonly:true
    });
    $('#lkt-form-noregistrasi').textbox({
        width:100,
        prompt:'auto',
        height:lktControlHeight,
        readonly:true
    });
    $('#lkt-form-status').textbox({
        width:97,
        prompt:'auto',
        height:lktControlHeight,
        readonly:true
    });
    $('#lkt-form-layanan').combobox({
        width:250,
        height:lktControlHeight,
        valueField:'yan_id',
        textField:'yan_label',
        showItemIcon:true,
        editable:false,
        panelHeight:'auto',
        onSelect:function(record) {
            if (record.jenis == 1) {
                $('#lkt-form-hewan').parent().parent().hide();
                $('#lkt-form-manusia').combobox('readonly', false);
                $('#lkt-form-manusia-label').html('Pasien:');
                lktShowClinicOnlyForm(true);
            }
            else {
                $('#lkt-form-hewan').parent().parent().show();
                $('#lkt-form-manusia').combobox('readonly', true);
                $('#lkt-form-manusia-label').html('Pemilik:');
                lktShowClinicOnlyForm(false);
            }
        },
        onChange:function() {
            lktSetEdited();
            //////////////////////////////////////////////////////////////////////////////////
            // bagian ini dipending dulu karena di RSA belum dipakai
            //////////////////////////////////////////////////////////////////////////////////
            // var yan_id = $(this).combobox('getValue');
            // $('#lkt-form-rusuktindakan').combobox('reload', 'tindakan/read?yan_id='+yan_id);
        },
        data:globalConfig.layanan,
        keyHandler:comboboxKeyHandler('#lkt-form-hewan')
    });
    $('#lkt-form-hewan').combobox({
        width:150,
        height:lktControlHeight,
        prompt:'Ketik minimal 3 huruf untuk mencari',
        valueField:'hwn_id',
        textField:'hwn_nama',
        panelHeight:'auto',
        panelMaxHeight:200,
        mode:'remote',
        method:'post',
        queryParams:{
            db:getDB(),
            hwn_com_id:globalConfig.com_id
        },
        url:getRestAPI('hewan/search'),
        onSelect:function(record) {
            if (lktChangeByUser) {
                $('#lkt-form-manusia').combobox('loadData',[{
                    man_id:record.man_id,
                    man_nama:record.man_nama
                }]);
                $('#lkt-form-manusia').combobox('setValue', record.man_id);
            }
        },
        onChange:function() {lktSetEdited();},
        keyHandler:comboboxKeyHandler('#lkt-form-manusia')
    });
    $('#lkt-form-manusia').combobox({
        width:200,
        height:lktControlHeight,
        prompt:'Ketik minimal 1 huruf untuk mencari',
        required:true,
        missingMessage:'Data mandatory, wajib diisi',
        valueField:'man_id',
        textField:'man_nama',
        panelHeight:'auto',
        panelMaxHeight:200,
        mode:'remote',
        method:'post',
        queryParams:{
            db:getDB(),
            man_com_id:globalConfig.com_id
        },
        url:getRestAPI('manusia/search'),
        onSelect:function(record) {
            $('#lkt-form-pembayaran').combobox('setValue', record.man_pembayaran);
            lktLoadDokter();
            if (record.man_pembayaran != 'Umum')
                $('#lkt-form-noasuransi').textbox('setValue', record.man_noasuransi);
            else
                $('#lkt-form-noasuransi').textbox('setValue', '');
        },
        onChange:function() {
            lktSetEdited();
        },
        keyHandler:comboboxKeyHandler('#lkt-form-tgcheckin')
    });
    $('#lkt-form-tgcheckin').datebox({
        width:120,
        height:lktControlHeight,
        editable:false,
        onChange:function() {lktSetEdited()},
        inputEvents:textboxInputEvents('#lkt-form-noantrian')
    });
    $('#lkt-form-noantrian').textbox({
        width:60,
        height:lktControlHeight,
        onChange:function() {lktTextboxOnChange(this)},
        inputEvents:textboxInputEvents('#lkt-form-dokter')
    });
    if (globalConfig.lok_jenis == 2)
    $('#lkt-form-dokter-label').html('Dokter hewan');
    $('#lkt-form-dokter').combobox({
        width:'auto',
        height:lktControlHeight,
        valueField:'sdm_id',
        textField:'sdm_nama',
        editable:false,
        panelHeight:'auto',
        panelMaxHeight:(2+12+2)*10, // 2->padding, 12->font-height, 10->jumlh item maks 
        onChange:function() {
            lktSetEdited();
        },
        onSelect:function(record) {
            var color = 'black';
            var decor = '';
            if (record.invalid != undefined && record.invalid) {
                color = 'red';
                decor = 'line-through';
                $(this).textbox('textbox').tooltip({
                    content:'Dokter sudah tidak aktif atau tidak sesuai dengan jenis kelamin.'+
                        '<br>Silahkan disesuaikan sendiri jika dimungkinkan'
                });
            }
            else
                $(this).textbox('textbox').tooltip('destroy');
            $(this).textbox('textbox').css('color', color);
            $(this).textbox('textbox').css('text-decoration', decor);
        },
        keyHandler:comboboxKeyHandler('#lkt-form-jeniskelamin')
    });
    $('#lkt-form-jeniskelamin').checkbox({
        checked:true,
        label:'Berdasar jenis kelamin',
        labelPosition:'after',
        labelWidth:200,
        onChange:function() {
            lktLoadDokter();
        },
        inputEvents:textboxInputEvents('#lkt-form-keperluan')
    });
    if (globalConfig.lok_jenis == 2)
        $('#lkt-form-jeniskelamin').parent().hide();
    $('#lkt-form-keperluan').combobox({
        width:150,
        height:lktControlHeight,
        valueField:'prl_id',
        textField:'prl_nama',
        editable:false,
        panelHeight:'auto',
        onChange:function() {lktSetEdited();},
        url:getRestAPI('master/keperluan'), // tidak membaca database tapi json di model
        keyHandler:comboboxKeyHandler('#lkt-form-pembayaran')
    });
    $('#lkt-form-pembayaran').combobox({
        width:150,
        height:lktControlHeight,
        valueField:'byr_id',
        textField:'byr_nama',
        editable:false,
        panelHeight:'auto',
        onSelect:function(record) {
            var prev = lktChangeByUser;
            lktChangeByUser = false;
            $('#lkt-form-mitra').combobox('setValue', record.byr_mitra);
            if (record.byr_mitra == '') $('#lkt-form-noasuransi').textbox('disable');
            else $('#lkt-form-noasuransi').textbox('enable');
            lktChangeByUser = prev;
        },
        onChange:function() {lktSetEdited();},
        url:getRestAPI('master/pembayaran'), // tidak membaca database tapi json di model
        keyHandler:comboboxKeyHandler('#lkt-form-mitra')
    });
    $('#lkt-form-mitra').combobox({
        width:150,
        height:lktControlHeight,
        valueField:'mit_id',
        textField:'mit_nama',
        readonly:true,
        editable:false,
        panelHeight:'auto',
        onChange:function() {lktSetEdited();},
        url:getRestAPI('master/mitra'), // tidak membaca database tapi json di model
        keyHandler:comboboxKeyHandler('#lkt-form-noasuransi')
    });
    $('#lkt-form-noasuransi').textbox({
        width:150,
        height:lktControlHeight,
        onChange:function() {lktTextboxOnChange(this)},
        inputEvents:textboxInputEvents('#lkt-form-rujukan')
    });
    $('#lkt-form-rujukan').combobox({
        width:120,
        height:lktControlHeight,
        valueField:'ru_id',
        textField:'ru_nama',
        editable:false,
        panelHeight:'auto',
        onSelect:function(record) {
            lktRujukan = record;
            // if (lktRujukan.ru_rujukanmasuk) lktEnableRujukanOnlyForm('enable');
            // else lktEnableRujukanOnlyForm('disable');
            //////////////////////////////////////////////////////////////////////
            // Rujukan masuk didisable dulu atas permintaan Pak Dir RS
            // karena dinilai akan merepotkan petugas entri
            // Datanya nanti akan didapat dari bridging dengen BPJS di fase 2
            //////////////////////////////////////////////////////////////////////
            lktEnableRujukanOnlyForm('disable');
        },
        onChange:function() {lktSetEdited();},
        url:getRestAPI('master/rujukan'), // tidak membaca database tapi json di model
        keyHandler:comboboxKeyHandler('#lkt-form-rusukno')
    });
    $('#lkt-form-rusukno').textbox({
        width:200,
        height:lktControlHeight,
        onChange:function() {lktTextboxOnChange(this)},
        inputEvents:textboxInputEvents('#lkt-form-rusuknomitra')
    });
    $('#lkt-form-rusuknomitra').textbox({
        width:200,
        height:lktControlHeight,
        onChange:function() {lktTextboxOnChange(this)},
        inputEvents:textboxInputEvents('#lkt-form-rusukanamnesa')
    });
    $('#lkt-form-rusukanamnesa').textbox({
        width:300,
        height:65,
        multiline:true,
        onChange:function() {lktSetEdited();},
    });
    $('#lkt-form-rusukcekfisik').textbox({
        width:300,
        height:60,
        multiline:true,
        onChange:function() {lktSetEdited();},
    });
    $('#lkt-form-rusukcekpenunjang').textbox({
        width:300,
        height:40,
        multiline:true,
        onChange:function() {lktSetEdited();},
    });
    $('#lkt-form-rusukpenyakit').combobox({
        width:300,
        height:lktControlHeight,
        prompt:'Ketik minimal 3 huruf untuk mencari',
        valueField:'kit_id',
        textField:'kit_nama',
        panelHeight:'auto',
        panelMaxHeight:200,
        mode:'remote',
        method:'POST',
        queryParams:{
            db:getDB()
        },
        url:getRestAPI('penyakit/search'),
        onChange:function() {lktSetEdited();},
        keyHandler:comboboxKeyHandler('#lkt-form-rusuktg')
    });
    $('#lkt-form-rusuktg').datetimebox({
        width:'auto',
        editable:false,
        showSeconds:false,
        height:lktControlHeight,
        onChange:function() {lktSetEdited()},
        inputEvents:textboxInputEvents('#lkt-form-rusuktindakan')
    });
    $('#lkt-form-rusuktindakan').combobox({
        width:'auto',
        height:lktControlHeight,
        valueField:'tin_id',
        textField:'tin_nama',
        editable:false,
        panelHeight:'auto',
        panelMaxHeight:195,
        onChange:function() {lktSetEdited();},
        //////////////////////////////////////////////////////////////////////////////////
        // bagian ini dipending dulu karena di RSA belum dipakai
        //////////////////////////////////////////////////////////////////////////////////
        // queryParams:{db:globalConfig.login_data?globalConfig.login_data.db:null},
        // url:'tindakan/read',
        keyHandler:comboboxKeyHandler('#lkt-form-rusukalasan')
    });
    $('#lkt-form-rusukalasan').combobox({
        width:'auto',
        height:lktControlHeight,
        valueField:'als_id',
        textField:'als_nama',
        editable:false,
        panelHeight:'auto',
        onChange:function() {lktSetEdited();},
        url:getRestAPI('master/alasanrujukan'), // tidak membaca database tapi json di model
        keyHandler:comboboxKeyHandler('#lkt-form-rusukalasan')
    });
    $('#lkt-form-rusukpenerima').combobox({
        width:'auto',
        height:lktControlHeight,
        valueField:'sdm_id',
        textField:'sdm_nama',
        editable:false,
        panelHeight:'auto',
        onChange:function() {lktSetEdited();},
        queryParams:{
            db:getDB(),
            com_id:globalConfig.com_id
        },
        url:getRestAPI('sdm/read'),
        keyHandler:comboboxKeyHandler('#lkt-form-rusukpengirim')
    });
    $('#lkt-form-rusukpengirim').textbox({
        width:'auto',
        height:lktControlHeight,
        onChange:function() {lktTextboxOnChange(this)},
        inputEvents:textboxInputEvents('#lkt-form-rusukunitpengirim')
    });
    $('#lkt-form-rusukunitpengirim').textbox({
        width:'auto',
        height:lktControlHeight,
        onChange:function() {lktTextboxOnChange(this)},
        inputEvents:textboxInputEvents('#lkt-form-rusuktelponpengirim')
    });
    $('#lkt-form-rusuktelponpengirim').textbox({
        width:'auto',
        height:lktControlHeight,
        onChange:function() {lktTextboxOnChange(this)},
        inputEvents:textboxInputEvents('#lkt-form-rusukunitmanusia')
    });
    $('#lkt-grid').datagrid({
        border:false,
        singleSelect:true,
        pagination:true,
        pageSize:50,
        toolbar:'#lkt-grid-tb',
        fit:true,
        rowStyler:function(index,row) {
            var str = '';
            $.each(globalConfig.kun_status, function(index, obj) {
                if (obj.sta_id == row.kun_status) {
                    str = 'color:'+obj.sta_color;
                    if (obj.sta_bold == 1)
                        str += ';font-weight:bold';
                    return;
                }
            });
            if (row.man_norm >= 99999995) str += 'font-style:italic;';
            return str;
        },
        columns:[[{
            field:'kun_id',
            title:'ID',
            resizable:false,
            width:100,
            hidden:true,
        },{
            field:'kun_noregistrasi',
            title:'No. Registrasi',
            resizable:false,
            width:100
        },{
            field:'kun_man_id',
            title:globalConfig.lok_jenis == 2?'Pemilik Hewan':'Pasien',
            formatter:function(value, row) {
                var nama = row.man_nama;
                if (globalConfig.lok_jenis == 3 && row.hwn_nama)
                    nama += (' ('+row.hwn_nama+')');
                return nama;
            },
            resizable:false,
            width:150
        },{
            field:'kun_hwn_id',
            title:'Pasien Hewan',
            formatter:function(value, row) {return row.hwn_nama;},
            resizable:false,
            width:150,
            hidden:globalConfig.lok_jenis != 2
        },{
            field:'kun_yan_id',
            title:'Layanan (Klinik)',
            formatter:function(value, row) {return row.yan_nama;},
            resizable:false,
            width:200
        },{
            field:'kun_tgcheckin',
            title:'Tgl Masuk',
            resizable:false,
            width:90
        },{
            field:'kun_tgcheckout',
            title:'Tgl Keluar',
            resizable:false,
            width:90
        },{
            field:'kun_noantrian',
            title:'No. antrian',
            resizable:false,
            width:90
        },{
            field:'kun_status',
            title:'Status',
            resizable:false,
            width:90
        },{
            field:'kun_dokter_sdm_id',
            title:'Dokter',
            formatter:function(value, row) {return row.dokter_sdm_nama;},
            resizable:false,
            width:90
        },{
            field:'kun_keperluan',
            title:'Keperluan',
            resizable:false,
            hidden:globalConfig.lok_jenis == 2,
            width:120
        },{
            field:'kun_pembayaran',
            title:'Pembayaran',
            resizable:false,
            hidden:globalConfig.lok_jenis == 2,
            width:120
        },{
            field:'kun_mitra',
            title:'Mitra',
            resizable:false,
            hidden:globalConfig.lok_jenis == 2,
            width:100
        },{
            field:'kun_noasuransi',
            title:'No. asuransi',
            resizable:false,
            hidden:globalConfig.lok_jenis == 2,
            width:120
        },{
            field:'kun_rujukan',
            title:'Rujukan',
            resizable:false,
            hidden:globalConfig.lok_jenis == 2,
            width:120
        }]],
        queryParams:{
            lok_id:globalConfig.login_data?globalConfig.login_data.lok_id:null,
            db:getDB()
        },
        url:getRestAPI('kunjungan/read'),
        onLoadSuccess:function(data) {
            if (data.rows.length > 0) $(this).datagrid('selectRow', 0);
            if (globalConfig.lok_jenis != 2)
                $('#lkt-form-hewan').parent().parent().hide();
            $('#lkt-form-noantrian').textbox('textbox').attr('maxlength', 5);
            $('#lkt-form-noasuransi').textbox('textbox').attr('maxlength', 16);
            $('#lkt-form-rusukno').textbox('textbox').attr('maxlength', 16);
            $('#lkt-form-rusuknomitra').textbox('textbox').attr('maxlength', 16);
            $('#lkt-form-rusukanamnesa').textbox('textbox').attr('maxlength', 200);
            $('#lkt-form-rusukcekfisik').textbox('textbox').attr('maxlength', 200);
            $('#lkt-form-rusukcekpenunjang').textbox('textbox').attr('maxlength', 200);
            $('#lkt-form-rusukpengirim').textbox('textbox').attr('maxlength', 30);
            $('#lkt-form-rusukunitpengirim').textbox('textbox').attr('maxlength', 30);
            $('#lkt-form-rusuktelponpengirim').textbox('textbox').attr('maxlength', 15);
        },
        onSelect:function(index, row) {
            lktChangeByUser = false;
            
            if(lktEditedId==-1)
            {
                lktFilledValue(row);
            }
            if(lktIndexNow!=index&&lktEditedId==0){
                $.messager.alert(globalConfig.app_nama, "Anda sedang dalam mode Tambah");
                $('#lkt-grid').datagrid('selectRow', lktIndexNow);
                lktGetValue();
            }
            if(lktIndexNow!=index&&lktEditedId>=1){
                $.messager.alert(globalConfig.app_nama, "Anda sedang dalam mode Edit");
                $('#lkt-grid').datagrid('selectRow', lktIndexNow);
                lktGetValue();
            }
            
            //lktLoadDokter();
            lktChangeByUser = true;
            $('#lkt-btn-del').linkbutton('enable');
            $('#lkt-form-layanan').textbox('textbox').focus();
        },
        onHeaderContextMenu:function(e,field) {
            e.preventDefault();
            if (field=='kun_noregistrasi') {
                var row = $(this).datagrid('getSelected');
                if (row) showID(row.kun_id);
            }
        } 
    });

    function lktFilledValue(row){
        $('#lkt-form-id').textbox('setValue', row.kun_id);
        $('#lkt-form-noregistrasi').textbox('setValue', row.kun_noregistrasi);
        $('#lkt-form-status').textbox('setValue', row.kun_status);
        $('#lkt-form-layanan').combobox('setValue', row.kun_yan_id);
        if (globalConfig.lok_jenis == 2) {
            $('#lkt-form-hewan').combobox('loadData',[{
                hwn_id:row.kun_hwn_id,
                hwn_nama:row.hwn_nama
            }]);
            $('#lkt-form-hewan').combobox('setValue', row.kun_hwn_id);
        }
        $('#lkt-form-manusia').combobox('loadData',[{
            man_id:row.kun_man_id, 
            man_nama:row.man_nama, 
            man_kelamin:row.man_kelamin
        }]);
        $('#lkt-form-manusia').combobox('setValue', row.kun_man_id);
        $('#lkt-form-dokter').combobox('setValue', row.kun_dokter_sdm_id);
        $('#lkt-form-tgcheckin').datebox('setValue', row.kun_tgcheckin);
        $('#lkt-form-noantrian').textbox('setValue', row.kun_noantrian);
        $('#lkt-form-jeniskelamin').checkbox('setValue', row.kun_jeniskelamin);
        $('#lkt-form-keperluan').combobox('setValue', row.kun_keperluan);
        $('#lkt-form-pembayaran').combobox('setValue', row.kun_pembayaran);
        $('#lkt-form-mitra').combobox('setValue', row.kun_mitra);
        $('#lkt-form-noasuransi').textbox('setValue', row.kun_noasuransi);
        $('#lkt-form-rujukan').combobox('setValue', row.kun_rujukan);
        $('#lkt-form-rusukno').textbox('setValue', row.ru_nomor);
        $('#lkt-form-rusuknomitra').textbox('setValue', row.ru_nomormitra);
        $('#lkt-form-rusuktg').datetimebox('setValue', row.ru_tanggal);
        $('#lkt-form-rusukanamnesa').textbox('setValue', row.ru_anamnesa);
        $('#lkt-form-rusukcekfisik').textbox('setValue', row.ru_cekfisik);
        $('#lkt-form-rusukcekpenunjang').textbox('setValue', row.ru_cekpenunjang);
        $('#lkt-form-rusukpenyakit').combobox('loadData',[{
            kit_id:row.ru_kit_id,
            kit_nama:row.kit_nama
        }]);
        $('#lkt-form-rusukpenyakit').combobox('setValue', row.ru_kit_id);
        $('#lkt-form-rusuktindakan').combobox('setValue', row.ru_tin_id);
        $('#lkt-form-rusukalasan').combobox('setValue', row.ru_alasan);
        $('#lkt-form-rusukpenerima').combobox('setValue', row.ru_sdm_id);
        $('#lkt-form-rusukpengirim').textbox('setValue', row.ru_pengirim);
        $('#lkt-form-rusukunitpengirim').textbox('setValue', row.ru_unitpengirim);
        $('#lkt-form-rusuktelponpengirim').textbox('setValue', row.ru_telponpengirim);
    }
    function lktAddPasien() {
        if($('#man-btn-add').length){
            $('#man-btn-add').click();
            $('#man-btn-add').linkbutton('options').programmatically=true;
        }
    }
    function lktGetValue(){
        var row=[];
        row.kun_noregistrasi=$('#lkt-form-noregistrasi').textbox('getValue');
        row.kun_status=$('#lkt-form-status').textbox('getValue');
        row.kun_man_id=$('#lkt-form-manusia').combobox('getValue');
        row.man_nama=$('#lkt-form-manusia').combobox('getText');
        row.kun_hwn_id=globalConfig.lok_jenis==2?$('#lkt-form-hewan').combobox('getValue'):null;
        row.hwn_nama=globalConfig.lok_jenis==2?$('#lkt-form-hewan').combobox('getText'):null;
        row.kun_yan_id=$('#lkt-form-layanan').combobox('getValue');
        row.kun_tgcheckin=$('#lkt-form-tgcheckin').datebox('getValue');
        row.kun_noantrian=$('#lkt-form-noantrian').textbox('getValue');
        row.kun_keperluan=$('#lkt-form-keperluan').combobox('getValue');
        row.kun_dokter_sdm_id=$('#lkt-form-dokter').combobox('getValue');
        row.dokter_sdm_nama=$('#lkt-form-dokter').combobox('getText');
        row.kun_pembayaran=$('#lkt-form-pembayaran').combobox('getValue');
        row.kun_mitra=$('#lkt-form-mitra').combobox('getValue');
        row.kun_noasuransi=$('#lkt-form-noasuransi').textbox('getValue');
        row.kun_rujukan=$('#lkt-form-rujukan').combobox('getValue');
        row.ru_rujukanmasuk=lktRujukan?lktRujukan.ru_rujukanmasuk:null;
        row.ru_nomor=$('#lkt-form-rusukno').textbox('getValue');
        row.ru_nomormitra=$('#lkt-form-rusuknomitra').textbox('getValue');
        row.ru_tanggal=$('#lkt-form-rusuktg').datetimebox('getValue');
        row.ru_anamnesa=$('#lkt-form-rusukanamnesa').textbox('getValue');
        row.ru_cekfisik=$('#lkt-form-rusukcekfisik').textbox('getValue');
        row.ru_cekpenunjang=$('#lkt-form-rusukcekpenunjang').textbox('getValue');
        row.ru_kit_id=$('#lkt-form-rusukpenyakit').combobox('getValue');
        row.kit_nama=$('#lkt-form-rusukpenyakit').combobox('getText');
        row.ru_tin_id=$('#lkt-form-rusuktindakan').combobox('getValue');
        row.ru_alasan=$('#lkt-form-rusukalasan').combobox('getValue');
        row.ru_sdm_id=$('#lkt-form-rusukpenerima').combobox('getValue');
        row.ru_pengirim=$('#lkt-form-rusukpengirim').textbox('getValue');
        row.ru_unitpengirim=$('#lkt-form-rusukunitpengirim').textbox('getValue');
        row.ru_telponpengirim=$('#lkt-form-rusuktelponpengirim').textbox('getValue');
        lktFilledValue(row);
    }
    function lktAdd() {
        lktChangeByUser = false;
        var today = new Date();
        var keperluan = $('#lkt-form-keperluan').combobox('getData');
        var pembayaran = $('#lkt-form-pembayaran').combobox('getData');
        var rujukan = $('#lkt-form-rujukan').combobox('getData');
        var tindakan = $('#lkt-form-rusuktindakan').combobox('getData');
        var alasan = $('#lkt-form-rusukalasan').combobox('getData');
        $('#lkt-form-id').textbox('setValue', '');
        $('#lkt-form-noregistrasi').textbox('setValue', '');
        $('#lkt-form-status').textbox('setValue', '');
        $('#lkt-form-layanan').combobox('setValue', globalConfig.layanan[0].yan_id);
        $('#lkt-form-manusia').combobox('setValue', '');
        if (globalConfig.lok_jenis == 2)
            $('#lkt-form-hewan').combobox('setValue', '');
        $('#lkt-form-tgcheckin').datebox('setValue', today.toLocaleDateString());
        $('#lkt-form-noantrian').textbox('setValue', '');
        $('#lkt-form-keperluan').combobox('setValue', keperluan.length?keperluan[0].prl_id:'');
        $('#lkt-form-dokter').combobox('setValue', '');
        $('#lkt-form-pembayaran').combobox('setValue', pembayaran.length?pembayaran[0].byr_id:'');
        $('#lkt-form-mitra').combobox('setValue', '');
        $('#lkt-form-noasuransi').textbox('setValue', '');
        $('#lkt-form-rujukan').combobox('setValue', rujukan.length?rujukan[0].ru_id:'');
        $('#lkt-form-rusukno').textbox('setValue', '');
        $('#lkt-form-rusuknomitra').textbox('setValue', '');
        $('#lkt-form-rusuktg').datetimebox('setValue',
            today.toLocaleDateString()+' '+today.getHours()+':'+today.getMinutes());
        $('#lkt-form-rusukanamnesa').textbox('setValue', '');
        $('#lkt-form-rusukcekfisik').textbox('setValue', '');
        $('#lkt-form-rusukcekpenunjang').textbox('setValue', '');
        $('#lkt-form-rusukpenyakit').combobox('setValue', '');
        $('#lkt-form-rusuktindakan').combobox('setValue', tindakan.length?tindakan[0].tin_id:'');
        $('#lkt-form-rusukalasan').combobox('setValue', alasan.length?alasan[0].als_id:'');
        $('#lkt-form-rusukpenerima').combobox('setValue', '');
        $('#lkt-form-rusukpengirim').textbox('setValue', '');
        $('#lkt-form-rusukunitpengirim').textbox('setValue', '');
        $('#lkt-form-rusuktelponpengirim').textbox('setValue', '');
        lktChangeByUser = true;
        lktEditedId = 0; // mode tambah
        lktSetEnableDisable();
        $('#lkt-form-layanan').textbox('textbox').focus();
    }

    function lktSave() {
        if (isDemo()) return;
        if (!lktIsValid()) {
            $.messager.alert(globalConfig.app_nama,
                "Silahkan diisi dulu data-data yang mandatory");
            return;
        }
        var data = {
            lok_id:globalConfig.login_data.lok_id,
            kun_id:lktEditedId,
            kun_noregistrasi:$('#lkt-form-noregistrasi').textbox('getValue'),
            kun_status:$('#lkt-form-status').textbox('getValue'),
            kun_man_id:$('#lkt-form-manusia').combobox('getValue'),
            kun_hwn_id:globalConfig.lok_jenis==2?$('#lkt-form-hewan').combobox('getValue'):null,
            kun_yan_id:$('#lkt-form-layanan').combobox('getValue'),
            kun_tgcheckin:$('#lkt-form-tgcheckin').datebox('getValue'),
            kun_noantrian:$('#lkt-form-noantrian').textbox('getValue'),
            kun_keperluan:$('#lkt-form-keperluan').combobox('getValue'),
            kun_dokter_sdm_id:$('#lkt-form-dokter').combobox('getValue'),
            kun_pembayaran:$('#lkt-form-pembayaran').combobox('getValue'),
            kun_mitra:$('#lkt-form-mitra').combobox('getValue'),
            kun_noasuransi:$('#lkt-form-noasuransi').textbox('getValue'),
            kun_rujukan:$('#lkt-form-rujukan').combobox('getValue'),
            ru_rujukanmasuk:lktRujukan?lktRujukan.ru_rujukanmasuk:null,
            ru_nomor:$('#lkt-form-rusukno').textbox('getValue'),
            ru_nomormitra:$('#lkt-form-rusuknomitra').textbox('getValue'),
            ru_tanggal:$('#lkt-form-rusuktg').datetimebox('getValue'),
            ru_anamnesa:$('#lkt-form-rusukanamnesa').textbox('getValue'),
            ru_cekfisik:$('#lkt-form-rusukcekfisik').textbox('getValue'),
            ru_cekpenunjang:$('#lkt-form-rusukcekpenunjang').textbox('getValue'),
            ru_kit_id:$('#lkt-form-rusukpenyakit').combobox('getValue'),
            ru_tin_id:$('#lkt-form-rusuktindakan').combobox('getValue'),
            ru_alasan:$('#lkt-form-rusukalasan').combobox('getValue'),
            ru_sdm_id:$('#lkt-form-rusukpenerima').combobox('getValue'),
            ru_pengirim:$('#lkt-form-rusukpengirim').textbox('getValue'),
            ru_unitpengirim:$('#lkt-form-rusukunitpengirim').textbox('getValue'),
            ru_telponpengirim:$('#lkt-form-rusuktelponpengirim').textbox('getValue'),
            //edited by naufal
            username:globalConfig.login_data.username,
            db:getDB()
        }
        $.ajax({
            type:'POST',
            data:data,
            url:getRestAPI('kunjungan/save'),
            success:function(retval) {
                var obj = JSON.parse(retval);
                if (obj.status == 'success') {
                    if (lktEditedId == 0) {
                        $('#lkt-grid').datagrid('insertRow', {
                            index:0,
                            row:obj.row
                        });
                        $('#lkt-grid').datagrid('selectRow', 0);
                    }
                    else {
                        var selectedRow = $('#lkt-grid').datagrid('getSelected');
                        var index = $('#lkt-grid').datagrid('getRowIndex', selectedRow);
                        $('#lkt-grid').datagrid('updateRow', {
                            index:index,
                            row:obj.row
                        });
                        $('#lkt-grid').datagrid('selectRow', index);
                    }
                }
                else {
                    $.messager.alert(globalConfig.app_nama, obj.errmsg);
                }
                lktEditedId = -1;
                lktSetEnableDisable();
            }
        });
    }

    function lktCancel() {
        lktEditedId = -1;
        lktSetEnableDisable();
        var row = $('#lkt-grid').datagrid('getSelected');
        if (row)
            $('#lkt-grid').datagrid('selectRow', $('#lkt-grid').datagrid('getRowIndex', row));
    }

    function lktDelete() {
        alert('Maaf menu ini belum bisa dipakai');
    }

    function lktRepIdPas() {
        var row = $('#lkt-grid').datagrid('getSelected');
        if (row) $('#lkt-repidpas-dlg').dialog({
            title:'Cetak Identitas Pasien',
            width:500,
            height:600,
            closable:true,
            border:true,
            modal:true,
            content:'<embed src="https://jobs.reendoo.com/jasper/cetak_idpas.php'+
                '?varnoreg='+row.kun_noregistrasi+'" '+
                'width="100%" height="100%" type="application/pdf">'
        });
    }

    function lktRekap() {
        $('#lkt-rekap-dlg').dialog({
            title:'Rekap pendapatan kunjungan',
            width:700,
            height:500,
            closable:true,
            border:true,
            maximizable:true,
            modal:true,
            href:'main/rekapkarcis'
        });
    }

    function lktSetEdited() {
        if (lktEditedId == -1 && lktChangeByUser) {
            lktEditedId = $('#lkt-form-id').textbox('getValue');
            lktSetEnableDisable();
        }
    }

    function lktSetEnableDisable() {
        if (lktEditedId >= 0) { // mode tambah atau edit
            var selectedRow = $('#lkt-grid').datagrid('getSelected');
            var index = $('#lkt-grid').datagrid('getRowIndex', selectedRow);
            lktIndexNow=index;
            $('#lkt-btn-add').linkbutton('disable');
            $('#lkt-btn-save').linkbutton('enable');
            $('#lkt-btn-cancel').linkbutton('enable');
            $('#lkt-btn-del').linkbutton('disable');
        }
        else { // mode lihat, tombol hapus akan enable kalau ada row yg terselect
            $('#lkt-btn-add').linkbutton('enable');
            $('#lkt-btn-save').linkbutton('disable');
            $('#lkt-btn-cancel').linkbutton('disable');
            $('#lkt-btn-del').linkbutton('enable');
        }
    }

    function lktTextboxOnChange(obj) {
        if (!lktChangeByUser) return;
        var str = $(obj).textbox('getValue');
        str = setCapitalSentenceEveryWord(str);
        var prev = lktChangeByUser;
        lktChangeByUser = false;
        $(obj).textbox('setValue', str);
        lktChangeByUser = prev;
        lktSetEdited();
    }

    function lktShowClinicOnlyForm(show) {
        if (show) {
            $('#lkt-form-keperluan').parent().parent().parent().parent().show();
            $('#lkt-form-rusukanamnesa').parent().parent().parent().parent().show();
            $('#lkt-form-rusuktindakan').parent().parent().parent().parent().show();
        }
        else {
            $('#lkt-form-keperluan').parent().parent().parent().parent().hide();
            $('#lkt-form-rusukanamnesa').parent().parent().parent().parent().hide();
            $('#lkt-form-rusuktindakan').parent().parent().parent().parent().hide();
        }
    }

    function lktEnableRujukanOnlyForm(enable) {
        $('#lkt-form-rusukno').textbox(enable);
        $('#lkt-form-rusuknomitra').textbox(enable);
        $('#lkt-form-rusuktg').datetimebox(enable);
        $('#lkt-form-rusukanamnesa').textbox(enable);
        $('#lkt-form-rusukcekfisik').textbox(enable);
        $('#lkt-form-rusukcekpenunjang').textbox(enable);
        $('#lkt-form-rusukpenyakit').combobox(enable);
        $('#lkt-form-rusuktindakan').combobox(enable);
        $('#lkt-form-rusukalasan').combobox(enable);
        $('#lkt-form-rusukpenerima').combobox(enable);
        $('#lkt-form-rusukpengirim').textbox(enable);
        $('#lkt-form-rusukunitpengirim').textbox(enable);
        $('#lkt-form-rusuktelponpengirim').textbox(enable);
    }
    
    function lktIsValid() {
        return $('#lkt-form-manusia').textbox('isValid');
    }

    function lktIsDokterValid(data, sdm_id) {
        var exists = false;
        for(var i=0;i<data.length;i++) {
            if (data[i].sdm_id == sdm_id) {
                exists = true;
                break;
            }
        };
        return exists;
    }

    function lktLoadDokter() {
        var row = $('#lkt-grid').datagrid('getSelected');
        var opts = $('#lkt-form-jeniskelamin').checkbox('options');
        if (globalConfig.lok_jenis == 2) opts = false;
        $.ajax({
            type:'POST',
            async:false,
            url:getRestAPI('sdm/dokter'),
            data:{
                kelamin:opts.checked?row.man_kelamin:null,
                db:getDB(),
                com_id:globalConfig.com_id
            },
            success:function(retval) {
                var data = JSON.parse(retval);

                if (!lktIsDokterValid(data, row.kun_dokter_sdm_id)) {
                    data.push({
                        sdm_id:row.kun_dokter_sdm_id,
                        sdm_nama:row.dokter_sdm_nama,
                        invalid:true
                    });
                }
                $('#lkt-form-dokter').combobox('loadData', data);
                $('#lkt-form-dokter').combobox('setValue', row.kun_dokter_sdm_id);
            }
        });
    }
});