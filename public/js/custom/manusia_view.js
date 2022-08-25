// Minus satu artinya mode lihat
// 0 berarti mode tambah
// selain itu berarti sedang ada record yang diedit
var manEditedId = -1;

// Ini untuk menandai apakah event onChange pada textbox dan combobox
// ditrigger oleh code atau oleh user. Defaultnya oleh user
var manChangeByUser = true;
var manControlHeight = 24;
var manIndexNow;
var refresh;

$(function() {
    $('#man-btn-add').linkbutton({
        text:'Tambah',
        iconCls:'fa fa-plus-circle fa-lg',
        programmatically: false,
        onClick:function() {manAdd();}
    });
    //$('.linkbutton-value[name="old_id"]').attr("name", new_id);
    $('#man-btn-save').linkbutton({
        disabled:true,
        text:'Simpan',
        iconCls:'fa fa-check-circle fa-lg',
        onClick:function() {manSave();}
    });
    $('#man-btn-cancel').linkbutton({
        disabled:true,
        text:'Batal',
        iconCls:'fa fa-times-circle fa-lg',
        onClick:function() {manCancel();}
    });
    $('#man-btn-del').linkbutton({
        disabled:true,
        text:'Hapus',
        onClick:function() {manDelete();}
    });
    $('#man-btn-del').hide();
    $('#man-btn-kartupas').linkbutton({
        text:'Cetak kartu',
        iconCls:'fa fa-id-card fa-lg',
        onClick:function() {manRepKartuPas();}
    });
    if (globalConfig.lok_jenis == 2) $('#man-btn-kartupas').hide();
    $('#man-search-by').combobox({
        width:120,
        valueField:'src_id',
        textField:'src_nama',
        editable:false,
        panelHeight:'auto',
        onSelect:function(record) {
            $('#man-search').next().hide();
            $('#man-datesearch').next().hide();
            $('#man-datesearchok').hide();
            if (record.src_id == 'tglahir') {
                $('#man-datesearch').next().show();
                $('#man-datesearchok').show();
            }
            else
                $('#man-search').next().show();
        },
        onChange:function() {manSetEdited();},
        url:getRestAPI('master/searchby')+'?language='+globalConfig.login_data.lang, // tidak membaca database tapi json di model
        onLoadSuccess:function(data) {
            if(data.length) $(this).combobox('setValue', 'nama');
        }
    });
    $('#man-search').searchbox({
        prompt:'Ketik kunci pencarian',
        width:200,
        searcher:function(value) {
            $('#man-grid').datagrid('reload', {
                key_search:$('#man-search-by').combobox('getValue'),
                key_val:value,
                man_com_id:globalConfig.com_id,
                db:getDB()
            });
        }
    });
    $('#man-datesearch').datebox({
        width:110,
        editable:false
    });
    $('#man-datesearchok').linkbutton({
        text:'Cari',
        iconCls:'fa fa-search fa-lg',
        onClick:function() {
            $('#man-grid').datagrid('reload', {
                key_search:$('#man-search-by').combobox('getValue'),
                key_val:$('#man-datesearch').datebox('getValue'),
                man_com_id:globalConfig.com_id,
                db:getDB()
            });
        }
    });
    $('#man-form-id').textbox({
        width:80,
        prompt:'auto',
        height:manControlHeight,
        readonly:true,
    });
    $('#man-form-id').textbox('textbox').parent().hide();
    $('#man-form-norm').textbox({
        width:80,
        prompt:'auto',
        height:manControlHeight,
        readonly:true
    });
    $('#man-form-nama').textbox({
        width:175,
        height:manControlHeight,
        required:true,
        missingMessage:'Data mandatory, wajib diisi',
        onChange:function() {manTextboxOnChange(this)},
        inputEvents:textboxInputEvents('#man-form-kotalahir')
    });
    $('#man-form-kotalahir').combobox({
        width:200,
        height:manControlHeight,
        prompt:'Ketik minimal 3 huruf untuk mencari',
        valueField:'are_id',
        textField:'are_nama',
        panelHeight:'auto',
        panelMaxHeight:200,
        mode:'remote',
        method:'POST',
        url:getRestAPI('area/read'),
        queryParams:{
            db:getDB()
        },
        onChange:function() {manSetEdited();},
        keyHandler:comboboxKeyHandler('#man-form-tglahir')
    });
    $('#man-form-tglahir').datebox({
        width:110,
        editable:false,
        height:manControlHeight,
        onChange:function() {manTextboxOnChange(this)},
        inputEvents:textboxInputEvents('#man-form-kelamin')
    });
    $('#man-form-kelamin').combobox({
        width:120,
        height:manControlHeight,
        valueField:'kel_id',
        textField:'kel_nama',
        editable:false,
        panelHeight:'auto',
        onChange:function() {manSetEdited();},
        url:getRestAPI('master/kelamin')+'?language='+globalConfig.login_data.lang, // tidak membaca database tapi json di model
        keyHandler:comboboxKeyHandler('#man-form-alamatktp')
    });
    $('#man-form-alamatktp').textbox({
        width:300,
        height:manControlHeight,
        onChange:function() {manTextboxOnChange(this)},
        inputEvents:$.extend({}, $.fn.textbox.defaults.inputEvents, {
            keypress:function(e) {
                if (e.which == 13) { // enter
                    if ($('#man-form-alamatskrng').textbox('getValue') == '') {
                        manChangeByUser = false;
                        $('#man-form-alamatskrng').textbox('setValue',
                            $('#man-form-alamatktp').textbox('getValue'));
                        manChangeByUser = true;
                    }
                    $('#man-form-alamatskrng').textbox('textbox').focus();
                }
            }
        })
    });
    $('#man-form-alamatskrng').textbox({
        width:300,
        height:manControlHeight,
        required:true,
        missingMessage:'Data mandatory, wajib diisi',
        onChange:function() {manTextboxOnChange(this)},
        inputEvents:textboxInputEvents('#man-form-area')
    });
    $('#man-form-area').combobox({
        width:200,
        height:manControlHeight,
        prompt:'Ketik minimal 3 huruf untuk mencari',
        valueField:'are_id',
        textField:'are_nama',
        panelHeight:'auto',
        panelMaxHeight:200,
        mode:'remote',
        method:'POST',
        url:getRestAPI('area/read'),
        queryParams:{
            db:getDB()
        },
        onChange:function(){
            var params_are_id= $(this).combobox('getValue');
            $('#man-form-area-desa').combobox('setValue', '');
            $('#man-form-area-desa').combobox('options').queryParams={
                are_id:params_are_id,
                db:getDB()
            };
            manSetEdited();
        },
        keyHandler:comboboxKeyHandler('#man-form-area-desa')
    });
    $('#man-form-area-desa').combobox({
        width:200,
        height:manControlHeight,
        prompt:'Ketik minimal 3 huruf untuk mencari',
        valueField:'are_id',
        textField:'are_nama',
        panelHeight:'auto',
        panelMaxHeight:200,
        mode:'remote',
        method:'POST',
        url:getRestAPI('area/desaread'),
        queryParams:{
            db:getDB()
        },
        onChange:function() {manSetEdited();},
        keyHandler:comboboxKeyHandler('#man-form-dusun')
    });
    $('#man-form-dusun').textbox({
        width:150,
        height:manControlHeight,
        onChange:function() {manTextboxOnChange(this)},
        inputEvents:textboxInputEvents('#man-form-rw')
    });
    $('#man-form-rw').numberbox({
        width:50,
        height:manControlHeight,
        onChange:function() {manSetEdited();},
        inputEvents:textboxInputEvents('#man-form-rt')
    });
    $('#man-form-rt').numberbox({
        width:50,
        height:manControlHeight,
        onChange:function() {manSetEdited();},
        inputEvents:textboxInputEvents('#man-form-kodepos')
    });
    $('#man-form-kodepos').textbox({
        width:70,
        height:manControlHeight,
        onChange:function() {manSetEdited();},
        inputEvents:textboxInputEvents('#man-form-pembayaran')
    });
    $('#man-form-pembayaran').combobox({
        width:150,
        height:manControlHeight,
        valueField:'byr_id',
        textField:'byr_nama',
        editable:false,
        panelHeight:'auto',
        onChange:function() {
            manSetEdited();
            cekDataBPJS();
        },
        url:getRestAPI('master/pembayaran')+'?language='+globalConfig.login_data.lang, // tidak membaca database tapi json di model
        keyHandler:comboboxKeyHandler('#man-form-noasuransi')
    });
    $('#man-form-noasuransi').textbox({
        width:150,
        height:manControlHeight,
        required: false,
        onChange:function() {
            manTextboxOnChange(this);
            cekDataBPJS();
        },
        inputEvents:textboxInputEvents('#man-form-nik')
    });
    $('#man-bpjs-loading').hide();
    $('#man-form-nik').numberbox({
        width:150,
        height:manControlHeight,
        onChange:function() {manSetEdited();},
        inputEvents:textboxInputEvents('#man-form-nokk')
    });
    $('#man-form-nokk').numberbox({
        width:150,
        height:manControlHeight,
        onChange:function() {manSetEdited();},
        inputEvents:textboxInputEvents('#man-form-telpon')
    });
    $('#man-form-nokk').parent().parent().hide();
    $('#man-form-telpon').numberbox({
        width:150,
        height:manControlHeight,
        required:true,
        missingMessage:'Data mandatory, wajib diisi',
        prompt:'Pakai koma jika lebih dari satu',
        onChange:function() {manSetEdited();},
        inputEvents:textboxInputEvents('#man-form-kelamin'),
        formatter: formatPhoneNo,
        parser: parseInt
    });
    $('#man-form-goldarah').combobox({
        width:100,
        height:manControlHeight,
        valueField:'gol_id',
        textField:'gol_nama',
        editable:false,
        panelHeight:'auto',
        onChange:function() {manSetEdited();},
        url:getRestAPI('master/goldarah'), // tidak membaca database tapi json di model
        keyHandler:comboboxKeyHandler('#man-form-rmibu')
    });
    $('#man-form-rmibu').combobox({
        width:200,
        height:manControlHeight,
        prompt:'Ketik minimal 1 huruf untuk mencari',
        valueField:'man_id',
        textField:'man_norm_nama',
        panelHeight:'auto',
        panelMaxHeight:200,
        mode:'remote',
        method:'post',
        queryParams:{
            db:getDB(),
            man_com_id:globalConfig.com_id
        },
        url:getRestAPI('manusia/ibusearch'),
        onChange:function() {
            manSetEdited();
        },
        keyHandler:comboboxKeyHandler('#man-form-kebangsaan')
    });
    $('#man-form-kebangsaan').combobox({
        width:100,
        height:manControlHeight,
        valueField:'bgs_id',
        textField:'bgs_nama',
        editable:false,
        panelHeight:'auto',
        onChange:function() {manSetEdited();},
        url:getRestAPI('master/kebangsaan'), // tidak baca database tapi json di model
        keyHandler:comboboxKeyHandler('#man-form-sukubangsa')
    });
    $('#man-form-sukubangsa').combobox({
        width:130,
        height:manControlHeight,
        valueField:'suk_id',
        textField:'suk_nama',
        editable:false,
        panelHeight:'auto',
        onChange:function() {manSetEdited();},
        url:getRestAPI('master/sukubangsa'), // tidak baca database tapi json di model
        keyHandler:comboboxKeyHandler('#man-form-bahasa')
    });
    $('#man-form-bahasa').combobox({
        width:130,
        height:manControlHeight,
        valueField:'suk_id',
        textField:'suk_nama',
        editable:false,
        panelHeight:'auto',
        onChange:function() {manSetEdited();},
        url:getRestAPI('master/sukubangsa'), // tidak baca database tapi json di model
        keyHandler:comboboxKeyHandler('#man-form-pekerjaan')
    });
    $('#man-form-pekerjaan').combobox({
        width:150,
        height:manControlHeight,
        valueField:'ker_id',
        textField:'ker_nama',
        editable:true,
        panelHeight:'auto',
        panelMaxHeight:200,
        url:getRestAPI('master/pekerjaan'), // tidak baca database tapi json di model
        onChange:function() {manSetEdited();},
        keyHandler:$.extend({}, $.fn.combobox.defaults.keyHandler, {
            down:function(e) {
                $(this).combobox('showPanel');
                $.fn.combobox.defaults.keyHandler.down.call(this,e);
            },
            enter:function(e) {
                manPekerjaanValidation();
            }
        })
    });
    $('#man-form-pekerjaan').combobox('textbox').bind('blur', function(e) {
        manPekerjaanValidation();
    });
    $('#man-form-pendakhir').combobox({
        width:180,
        height:manControlHeight,
        valueField:'dik_id',
        textField:'dik_nama',
        editable:false,
        panelHeight:'auto',
        onChange:function() {manSetEdited();},
        url:getRestAPI('master/pendakhir')+'?language='+globalConfig.login_data.lang, 
        keyHandler:comboboxKeyHandler('#man-form-statusnikah')
    });
    $('#man-form-statusnikah').combobox({
        width:100,
        height:manControlHeight,
        valueField:'sta_id',
        textField:'sta_nama',
        editable:false,
        panelHeight:'auto',
        onChange:function() {manSetEdited();},
        url:getRestAPI('master/statusnikah')+'?language='+globalConfig.login_data.lang, // tidak baca database tapi json di model
        keyHandler:comboboxKeyHandler('#man-form-agama')
    });
    $('#man-form-agama').combobox({
        width:130,
        height:manControlHeight,
        valueField:'agm_id',
        textField:'agm_nama',
        editable:false,
        panelHeight:'auto',
        onChange:function() {manSetEdited();},
        url:getRestAPI('master/agama')+'?language='+globalConfig.login_data.lang, // tidak baca database tapi json di model
        keyHandler:comboboxKeyHandler('#man-form-namafam')
    });
    $('#man-form-namafam').textbox({
        width:150,
        height:manControlHeight,
        onChange:function() {manTextboxOnChange(this)},
        inputEvents:textboxInputEvents('#man-form-hubfam')
    });
    $('#man-form-hubfam').combobox({
        width:100,
        height:manControlHeight,
        valueField:'hub_id',
        textField:'hub_nama',
        editable:false,
        panelHeight:'auto',
        onChange:function() {manSetEdited();},
        url:getRestAPI('master/hubfam')+'?language='+globalConfig.login_data.lang, // tidak baca database tapi json di model
        keyHandler:comboboxKeyHandler('#man-form-alamatfam')
    });
    $('#man-form-alamatfam').textbox({
        width:'100%',
        height:manControlHeight,
        onChange:function() {manTextboxOnChange(this)},
        inputEvents:textboxInputEvents('#man-form-telpfam')
    });
    $('#man-form-telpfam').numberbox({
        width:200,
        height:manControlHeight,
        prompt:'Pakai koma jika lebih dari satu',
        onChange:function() {manSetEdited();},
        inputEvents:textboxInputEvents('#man-form-nama'),
        formatter: formatPhoneNo,
        parser: parseInt
    });

    //////////////////////////////////////////////////////
    // Ditambahkan oleh Fendik pada tgl 3 Nov 2021
    // untuk mengganti label No RM menjadi ID Pemilik,
    // dan menyembunyikan beberapa field yang kurang
    // relevan jika loginnya adalah SiReDisH

    if (globalConfig.lok_jenis == 2) {
        $('#man-form-normlabel').html('ID Pemilik');
        $('#man-form-pembayaran').parent().parent().hide();
        $('#man-form-noasuransi').parent().parent().hide();
        $('#man-form-goldarah').parent().parent().hide();
        $('#man-form-rmibu').parent().parent().hide();
        $('#man-form-sukubangsa').parent().parent().hide();
        $('#man-form-bahasa').parent().parent().hide();
        $('#man-form-statusnikah').parent().parent().hide();
        $('#man-form-agama').parent().parent().parent().hide();
    }

    //////////////////////////////////////////////////////

    $('#man-grid').datagrid({
        border:false,
        pagination:true,
        pageSize:50,
        toolbar:'#man-grid-tb',
        fit:true,
        singleSelect:true,
        rowStyler:function(index,row) {
            var style = '';
            if (row!=null && !row.available && globalConfig.lok_jenis!=2) style += 'color:red;';
            if (row!=null && row.man_norm>=999995) style += 'font-style:italic;'
            return style;
        },
        columns:[[{
            field:'man_id',
            title:'ID',
            resizable:false,
            width:35,
            hidden:true
        },{
            field:'man_norm',
            title:globalConfig.lok_jenis==2?'ID Pemilik':'No. RM',
            resizable:false,
            width:80
        },{
            field:'man_nama',
            title:'Nama',
            resizable:false,
            width:180
        },{
            field:'man_lahir_are_id',
            title:'Kota',
            resizable:false,
            formatter:function(value, row) {return row.are_kotalahir},
            width:120
        },{
            field:'man_tglahir',
            title:'Tgl lahir',
            resizable:false,
            width:85
        },{
            field:'man_umur',
            title:'Umur',
            resizable:false,
            width:140
        },{
            field:'man_kelamin',
            title:'Gender',
            resizable:false,
            width:60
        },{
            field:'man_alamatktp',
            title:'Alamat KTP',
            resizable:false,
            width:300
        },{
            field:'man_alamatskrng',
            title:'Alamat sekarang',
            resizable:false,
            width:300
        },{
            field:'man_are_id',
            title:'Kota',
            resizable:false,
            formatter:function(value, row) {return row.are_nama},
            width:120
        },{
            field:'man_kodepos',
            title:'Kode pos',
            resizable:false,
            width:80
        },{
            field:'man_pembayaran',
            title:'Pembayaran',
            resizable:false,
            width:120,
            hidden:globalConfig.lok_jenis==2
        },{
            field:'man_noasuransi',
            title:'No. asuransi',
            resizable:false,
            width:120,
            hidden:globalConfig.lok_jenis==2
        },{
            field:'man_nik',
            title:'NIK',
            resizable:false,
            width:150
        },{
            field:'man_nokk',
            title:'No. KK',
            resizable:false,
            width:120,
            hidden:true // disembunyikan atas saran dari Tim BASA karena merepotkan pasien
        },{
            field:'man_telpon',
            title:'Telpon',
            resizable:false,
            width:100
        },{
            field:'man_goldarah',
            title:'Gol. darah',
            resizable:false,
            width:100,
            hidden:globalConfig.lok_jenis==2
        },{
            field:'man_kebangsaan',
            title:'Kebangsaan',
            formatter:function(value, row) {return row.man_kebangsaan=='I'?'WNI':'WNA'},
            resizable:false,
            width:100,
            hidden:globalConfig.lok_jenis==2
        },{
            field:'man_sukubangsa',
            title:'Suku bangsa',
            resizable:false,
            width:100,
            hidden:globalConfig.lok_jenis==2
        },{
            field:'man_pekerjaan',
            title:'Pekerjaan',
            resizable:false,
            width:120
        },{
            field:'man_pendakhir',
            title:'Pendidikan',
            resizable:false,
            width:90
        },{
            field:'man_statusnikah',
            title:'Status nikah',
            resizable:false,
            width:100,
            hidden:globalConfig.lok_jenis==2
        },{
            field:'man_agama',
            title:'Agama',
            resizable:false,
            width:100,
            hidden:globalConfig.lok_jenis==2
        },{
            field:'man_namafam',
            title:'Nama famili',
            resizable:false,
            width:150,
            hidden:globalConfig.lok_jenis==2
        },{
            field:'man_hubfam',
            title:'Hub. famili',
            resizable:false,
            width:100,
            hidden:globalConfig.lok_jenis==2
        },{
            field:'man_alamatfam',
            title:'Alamat famili',
            resizable:false,
            width:300,
            hidden:globalConfig.lok_jenis==2
        },{
            field:'man_telpfam',
            title:'Telp. famili',
            resizable:false,
            width:100,
            hidden:globalConfig.lok_jenis==2
        }]],
        dataType: "json",
        queryParams:{
            db:getDB(),
            man_com_id:globalConfig.com_id
        },
        url:getRestAPI('manusia/read'),
        onLoadSuccess:function(data) {
            manEditedId=-1;
            if (data.rows.length > 0) $(this).datagrid('selectRow', 0);
            $('#man-form-nama').textbox('textbox').attr('maxlength', 30);
            $('#man-form-alamatktp').textbox('textbox').attr('maxlength', 60);
            $('#man-form-alamatskrng').textbox('textbox').attr('maxlength', 60);
            $('#man-form-dusun').textbox('textbox').attr('maxlength', 60);
            $('#man-form-rw').textbox('textbox').attr('maxlength', 3);
            $('#man-form-rt').textbox('textbox').attr('maxlength', 3);
            $('#man-form-kodepos').textbox('textbox').attr('maxlength', 5);
            $('#man-form-telpon').textbox('textbox').attr('maxlength', 45);
            $('#man-form-noasuransi').textbox('textbox').attr('maxlength', 30);
            $('#man-form-nik').textbox('textbox').attr('maxlength', 16);
            $('#man-form-nokk').textbox('textbox').attr('maxlength', 16);
            $('#man-form-namafam').textbox('textbox').attr('maxlength', 30);
            $('#man-form-alamatfam').textbox('textbox').attr('maxlength', 60);
            $('#man-form-telpfam').textbox('textbox').attr('maxlength', 45);
            manCancel();
            if (lktman==0) {
                manChangeByUser = true;
                manAdd();
            }
        },
        onSelect:function(index, row) {
            manChangeByUser = false;
            if(manEditedId==-1)
            {
                manFilledValue(row);
            }
            if(manIndexNow!=index&&manEditedId==0){
                $.messager.alert(globalConfig.app_nama, "Anda sedang dalam mode Tambah");
                $('#man-grid').datagrid('selectRow', manIndexNow);
                manGetValue();
            }
            if(manIndexNow!=index&&manEditedId>=1){
                $.messager.alert(globalConfig.app_nama, "Anda sedang dalam mode Edit");
                $('#man-grid').datagrid('selectRow', manIndexNow);
                manGetValue();
            }
            manChangeByUser = true;
            $('#man-btn-del').linkbutton('enable');
            if (row.man_norm >= 999995) {
                $('#man-form-nama').textbox('readonly', true);
                $('#man-form-kotalahir').textbox('textbox').focus();
            }
            else {
                $('#man-form-nama').textbox('readonly', false);
                $('#man-form-nama').textbox('textbox').focus();
            }
        }
    });

    getLabel();
    function manFilledValue(row){
        $('#man-form-id').textbox('setValue', row.man_id);
        $('#man-form-norm').textbox('setValue', row.man_norm);
        $('#man-form-nama').textbox('setValue', row.man_nama);
        var data = [];
        if (row.man_lahir_are_id) data = [{
            are_id:row.man_lahir_are_id,
            are_nama:row.are_kotalahir
        }];
        $('#man-form-kotalahir').combobox('loadData', data);
        $('#man-form-kotalahir').combobox('setValue', row.man_lahir_are_id);
        $('#man-form-tglahir').datebox('setValue', row.man_tglahir);
        $('#man-form-alamatktp').textbox('setValue', row.man_alamatktp);
        $('#man-form-alamatskrng').textbox('setValue', row.man_alamatskrng);
        $('#man-form-kodepos').textbox('setValue', row.man_kodepos);
        $('#man-form-area').combobox('loadData',[{
            are_id:row.man_are_id,
            are_nama:row.are_nama
        }]);
        $('#man-form-area').combobox('setValue', row.man_are_id);
        $('#man-form-area-desa').combobox('loadData',[{
            are_id:row.man_are_desa_id,
            are_nama:row.are_namadesa
        }]);
        $('#man-form-area-desa').combobox('setValue', row.man_are_desa_id);
        $('#man-form-dusun').textbox('setValue', row.man_dusun);
        $('#man-form-rw').numberbox('setValue', row.man_rw);
        $('#man-form-rt').numberbox('setValue', row.man_rt);
        $('#man-form-bahasa').combobox('setValue', row.man_bahasa);
        $('#man-form-pembayaran').combobox('setValue', row.man_pembayaran);
        $('#man-form-noasuransi').textbox('setValue', row.man_noasuransi);
        $('#man-form-nik').numberbox('setValue', row.man_nik);
        $('#man-form-nokk').numberbox('setValue', row.man_nokk);
        $('#man-form-telpon').numberbox('setValue', row.man_telpon);
        $('#man-form-kelamin').combobox('setValue', row.man_kelamin);
        $('#man-form-goldarah').combobox('setValue', row.man_goldarah);
        data2 = [];
        if (row.man_ibu_man_id) data2 = [{
            man_id:row.man_ibu_man_id,
            man_norm_nama:row.normibu+'-'+row.namaibu
        }];
        $('#man-form-rmibu').combobox('loadData', data2);
        $('#man-form-rmibu').combobox('setValue', row.man_ibu_man_id);
        $('#man-form-kebangsaan').combobox('setValue', row.man_kebangsaan);
        $('#man-form-sukubangsa').combobox('setValue', row.man_sukubangsa);
        $('#man-form-pekerjaan').combobox('setValue', row.man_pekerjaan);
        $('#man-form-pendakhir').combobox('setValue', row.man_pendakhir);
        $('#man-form-statusnikah').combobox('setValue', row.man_statusnikah);
        $('#man-form-agama').combobox('setValue', row.man_agama);
        $('#man-form-namafam').textbox('setValue', row.man_namafam);
        $('#man-form-hubfam').combobox('setValue', row.man_hubfam);
        $('#man-form-alamatfam').textbox('setValue', row.man_alamatfam);
        $('#man-form-telpfam').numberbox('setValue', row.man_telpfam);
    }

    function manGetValue(){
        var row=[];
        row.man_norm=$('#man-form-norm').textbox('getValue');
        row.man_nama=$('#man-form-nama').textbox('getValue');
        row.man_lahir_are_id=$('#man-form-kotalahir').combobox('getValue');
        row.are_kotalahir=$('#man-form-kotalahir').combobox('getText');
        row.man_tglahir=$('#man-form-tglahir').datebox('getValue');
        row.man_alamatktp=$('#man-form-alamatktp').textbox('getValue');
        row.man_alamatskrng=$('#man-form-alamatskrng').textbox('getValue');
        row.man_kodepos=$('#man-form-kodepos').textbox('getValue');
        row.man_are_id=$('#man-form-area').combobox('getValue');
        row.are_nama=$('#man-form-area').combobox('getText');
        row.man_are_desa_id=$('#man-form-area-desa').combobox('getValue');
        row.are_nama=$('#man-form-area-desa').combobox('getText');
        row.man_dusun=$('#man-form-dusun').textbox('getValue');
        row.man_rt=$('#man-form-rt').numberbox('getValue');
        row.man_rw=$('#man-form-rw').numberbox('getValue');
        row.man_bahasa=$('#man-form-bahasa').combobox('getValue');
        row.man_pembayaran=$('#man-form-pembayaran').combobox('getValue');
        row.man_noasuransi=$('#man-form-noasuransi').textbox('getValue');
        row.man_nik=$('#man-form-nik').numberbox('getValue');
        row.man_nokk=$('#man-form-nokk').numberbox('getValue');
        row.man_telpon=$('#man-form-telpon').numberbox('getValue');
        row.man_kelamin=$('#man-form-kelamin').combobox('getValue');
        row.man_goldarah=$('#man-form-goldarah').combobox('getValue');
        row.man_ibu_man_id=$('#man-form-rmibu').combobox('getValue');
        row.man_kebangsaan=$('#man-form-kebangsaan').combobox('getValue');
        row.man_sukubangsa=$('#man-form-sukubangsa').combobox('getValue');
        row.man_pekerjaan=$('#man-form-pekerjaan').combobox('getValue');
        row.man_pendakhir=$('#man-form-pendakhir').combobox('getValue');
        row.man_statusnikah=$('#man-form-statusnikah').combobox('getValue');
        row.man_agama=$('#man-form-agama').combobox('getValue');
        row.man_namafam=$('#man-form-namafam').textbox('getValue');
        row.man_hubfam=$('#man-form-hubfam').combobox('getValue');
        row.man_alamatfam=$('#man-form-alamatfam').textbox('getValue');
        row.man_telpfam=$('#man-form-telpfam').numberbox('getValue');
        var namaibu=$('#man-form-rmibu').combobox('getText').split("-");
        row.normibu=namaibu[0];
        row.namaibu=namaibu[1];
        manFilledValue(row);
    }

    function manAdd() {
        manChangeByUser = false;
        var today = new Date();
        $('#man-form-id').textbox('setValue', '');
        $('#man-form-norm').textbox('setValue', '');
        $('#man-form-nama').textbox('setValue', '');
        $('#man-form-kotalahir').combobox('setValue', '');
        $('#man-form-tglahir').datebox('setValue', today.toLocaleDateString());
        $('#man-form-alamatktp').textbox('setValue', '');
        $('#man-form-alamatskrng').textbox('setValue', '');
        $('#man-form-kodepos').textbox('setValue', '');
        $('#man-form-area').combobox('setValue', '');
        $('#man-form-area-desa').combobox('setValue', '');
        $('#man-form-dusun').textbox('setValue', '');
        $('#man-form-rw').numberbox('setValue', '');
        $('#man-form-rt').numberbox('setValue', '');
        $('#man-form-bahasa').combobox('setValue', 'Jawa');
        $('#man-form-pembayaran').combobox('setValue', '');
        $('#man-form-noasuransi').textbox('setValue', '');
        $('#man-form-nik').numberbox('setValue', '');
        $('#man-form-nokk').numberbox('setValue', '');
        $('#man-form-telpon').numberbox('setValue', '');
        $('#man-form-kelamin').combobox('setValue', 'L');
        $('#man-form-goldarah').combobox('setValue', '');
        $('#man-form-rmibu').combobox('setValue', '');
        $('#man-form-kebangsaan').combobox('setValue', 'I');
        $('#man-form-sukubangsa').combobox('setValue', 'Jawa');
        $('#man-form-pekerjaan').combobox('setValue', '');
        $('#man-form-pendakhir').combobox('setValue', '');
        $('#man-form-statusnikah').combobox('setValue', 'Nikah');
        $('#man-form-agama').combobox('setValue', 'Islam');
        $('#man-form-namafam').textbox('setValue', '');
        $('#man-form-hubfam').combobox('setValue', '');
        $('#man-form-alamatfam').textbox('setValue', '');
        $('#man-form-telpfam').numberbox('setValue', '');
        manChangeByUser = true;
        manEditedId = 0; // mode tambah
        manSetEnableDisable();
        $('#man-form-nama').textbox('readonly', false);
        $('#man-form-nama').textbox('textbox').focus();
    }

    function manSave() {
        if (isDemo()) return;
        if (!manIsValid()) {
            $.messager.alert(globalConfig.app_nama,
                "Silahkan diisi dulu data-data yang mandatory");
            return;
        }
        var data = {
            man_id:manEditedId,
            man_norm:$('#man-form-norm').textbox('getValue'),
            man_nama:$('#man-form-nama').textbox('getValue'),
            man_lahir_are_id:$('#man-form-kotalahir').combobox('getValue'),
            man_tglahir:$('#man-form-tglahir').datebox('getValue'),
            man_alamatktp:$('#man-form-alamatktp').textbox('getValue'),
            man_alamatskrng:$('#man-form-alamatskrng').textbox('getValue'),
            man_kodepos:$('#man-form-kodepos').textbox('getValue'),
            man_are_id:$('#man-form-area').combobox('getValue'),
            man_are_desa_id:$('#man-form-area-desa').combobox('getValue'),
            man_dusun:$('#man-form-dusun').textbox('getValue'),
            man_rt:$('#man-form-rt').numberbox('getValue'),
            man_rw:$('#man-form-rw').numberbox('getValue'),
            man_bahasa:$('#man-form-bahasa').combobox('getValue'),
            man_pembayaran:$('#man-form-pembayaran').combobox('getValue'),
            man_noasuransi:$('#man-form-noasuransi').textbox('getValue'),
            man_nik:$('#man-form-nik').numberbox('getValue'),
            man_nokk:$('#man-form-nokk').numberbox('getValue'),
            man_telpon:$('#man-form-telpon').numberbox('getValue'),
            man_kelamin:$('#man-form-kelamin').combobox('getValue'),
            man_goldarah:$('#man-form-goldarah').combobox('getValue'),
            man_ibu_man_id:$('#man-form-rmibu').combobox('getValue'),
            man_kebangsaan:$('#man-form-kebangsaan').combobox('getValue'),
            man_sukubangsa:$('#man-form-sukubangsa').combobox('getValue'),
            man_pekerjaan:$('#man-form-pekerjaan').combobox('getValue'),
            man_pendakhir:$('#man-form-pendakhir').combobox('getValue'),
            man_statusnikah:$('#man-form-statusnikah').combobox('getValue'),
            man_agama:$('#man-form-agama').combobox('getValue'),
            man_namafam:$('#man-form-namafam').textbox('getValue'),
            man_hubfam:$('#man-form-hubfam').combobox('getValue'),
            man_alamatfam:$('#man-form-alamatfam').textbox('getValue'),
            man_telpfam:$('#man-form-telpfam').numberbox('getValue'),
            username:globalConfig.login_data.username,
            db:getDB(),
            man_com_id:globalConfig.com_id
        }
        $.ajax({
            type:'POST',
            data:data,
            url:getRestAPI('manusia/save'),
            success:function(retval) {
                var obj = JSON.parse(retval);
                if (obj.status == 'success' && obj.row !=null) {
                    console.log(obj)
                    if (manEditedId == 0) {
                        $('#man-grid').datagrid('insertRow', {
                            index:0,
                            row:obj.row
                        });
                        $('#man-grid').datagrid('selectRow', 0);
                    }
                    else {
                        var selectedRow = $('#man-grid').datagrid('getSelected');
                        var index = $('#man-grid').datagrid('getRowIndex', selectedRow);
                        $('#man-grid').datagrid('updateRow', {
                            index:index,
                            row:obj.row
                        });
                        $('#man-grid').datagrid('selectRow', index);
                    }
                    if ($('#main-tab').length && $('#man-btn-add').linkbutton('options').programmatically == true){
                        $('#main-tab').tabs('select', 'Loket');
                        $('#man-btn-add').linkbutton('options').programmatically = false;
                    }
                }
                else if (obj.status == 'success' && obj.row ==null) {
                    $.messager.alert(globalConfig.app_nama, "Tidak boleh setelah tekan Tambah lalu edit baris yang baru ditambahkan");
                }
                else
                    $.messager.alert(globalConfig.app_nama, obj.errmsg);
                manEditedId = -1;
                manSetEnableDisable();
            }
        });
    }

    function manCancel() {
        manEditedId = -1;
        manSetEnableDisable();
        var row = $('#man-grid').datagrid('getSelected');
        if (row)
            $('#man-grid').datagrid('selectRow', $('#man-grid').datagrid('getRowIndex', row));
    }

    function manDelete() {
        $.messager.alert(globalConfig.app_nama, 'Maaf menu ini belum bisa dipakai');
    }

    function manRepKartuPas() {
        var row = $('#man-grid').datagrid('getSelected');
        if (row) $('#man-repkartu-dlg').dialog({
            title:'Cetak Kartu',
            width:500,
            height:350,
            closable:true,
            border:true,
            modal:true,
            content:'<embed src="https://jobs.reendoo.com/jasper/cetak_kartupas.php'+
                '?varnorm='+row.man_norm+
                '&varnama='+row.man_nama+
                '&varnik='+row.man_nik+
                '&varalamat='+row.man_alamatskrng+
                '&vartglahir='+row.man_tglahir+'" '+
                'width="100%" height="100%" type="application/pdf">'
        });
    }

    function manSetEdited() {
        if (manEditedId == -1 && manChangeByUser) {
            manEditedId = $('#man-form-id').textbox('getValue');
            manSetEnableDisable();
        }
    }

    function manSetEnableDisable() {
        if (manEditedId >= 0) { // mode tambah atau edit
            var selectedRow = $('#man-grid').datagrid('getSelected');
            var index = $('#man-grid').datagrid('getRowIndex', selectedRow);
            manIndexNow=index;
            $('#man-btn-add').linkbutton('disable');
            $('#man-btn-save').linkbutton('enable');
            $('#man-btn-cancel').linkbutton('enable');
            $('#man-btn-del').linkbutton('disable');
        }
        else { // mode lihat, tombol hapus akan enable kalau ada row yg terselect
            $('#man-btn-add').linkbutton('enable');
            $('#man-btn-save').linkbutton('disable');
            $('#man-btn-cancel').linkbutton('disable');
            $('#man-btn-del').linkbutton('enable');
        }
    }

    function manTextboxOnChange(obj) {
        if (!manChangeByUser) return;
        var str = $(obj).textbox('getValue');
        str = setCapitalSentenceEveryWord(str);
        manChangeByUser = false;
        $(obj).textbox('setValue', str);
        manChangeByUser = true;
        manSetEdited();
    }

    function manPekerjaanValidation() {
        var ob = $('#man-form-pekerjaan').combobox('getData');
        var nama = $('#man-form-pekerjaan').combobox('getText');
        var matchId = '';
        nama = nama.toLowerCase();
        $.each(ob, function(index, obi) {
            if(obi.ker_nama.toLowerCase() == nama) {
                matchId = obi.ker_id;
                return false;
            }
        });
        if (matchId == '') $.each(ob, function(index, obi) {
            if(obi.ker_nama.toLowerCase().indexOf(nama) >= 0) {
                matchId = obi.ker_id;
                return false;
            }
        });
        $('#man-form-pekerjaan').combobox('setValue', matchId);
        $('#man-form-pekerjaan').combobox('hidePanel');
        $.fn.combobox.defaults.keyHandler.down.call(this,e);
        $('#man-form-pendakhir').textbox('textbox').focus();
    }

    function manIsValid() {
        return $('#man-form-nama').textbox('isValid') &&
            $('#man-form-alamatskrng').textbox('isValid') &&
            $('#man-form-telpon').textbox('isValid') &&
            $('#man-form-noasuransi').textbox('isValid');
    }

    //Added by Dany
    function formatPhoneNo(val){
        const valStr = ""+val;
        if(valStr.charAt(0) !== "0"){
            return `0${valStr}`;
        }
        return valStr;
    }

    function cekDataBPJS(){
        if(manEditedId < 0){
            return;
        }
        const noAsuransi = $('#man-form-noasuransi').textbox('getValue')
        const tipeAuransi = $('#man-form-pembayaran').combobox('getValue');
        if(tipeAuransi && tipeAuransi.includes('BPJS Kes')){
            $('#man-form-noasuransi').textbox({required:true, missingMessage: "Harus Diisi"});
            if( noAsuransi && noAsuransi.length > 0){
                $('#man-bpjs-loading').show();
                $.ajax({
                    type:'POST',
                    data:{man_noasuransi: noAsuransi},
                    url:getRestAPI('manusia/get_bpjs'),
                    success:function(retval) {
                        $('#man-bpjs-loading').hide();
                        var obj = JSON.parse(retval);
                        if (obj.status == 'success' && obj.data !=null) {
                            //Set field berdasarkan data yang didapat dari API BPJS
                            if(obj.data.aktif == false || obj.data.aktif == 'false'){
                                alert('Status BPJS tidak aktif');
                                $('#man-form-noasuransi').textbox('setValue', '');
                                return;
                            }
                            $('#man-form-nama').textbox('setValue', obj.data.nama);
                            $('#man-form-noasuransi').textbox('setValue', obj.data.noKartu);
                            if(obj.data.tglLahir){
                                const detailTanggal = obj.data.tglLahir.split("-");
                                const newDate = (new Date(`${detailTanggal[2]}-${detailTanggal[1]}-${detailTanggal[0]}`)).toLocaleDateString();
                                console.log("newDate = "+newDate);
                                $('#man-form-tglahir').datebox('setValue', newDate);
                            }
                            if(obj.data.noKTP){
                                $('#man-form-nik').numberbox('setValue', obj.data.noKTP);
                            }
                            if(obj.data.noHP){
                                $('#man-form-telpon').numberbox('setValue', obj.data.noHP);
                            }
                            if(obj.data.sex){
                                $('#man-form-kelamin').combobox('setValue', obj.data.sex);
                            }
                            if(obj.data.golDarah){
                                $('#man-form-goldarah').combobox('setValue', obj.data.golDarah);
                            }
                            alert(`Ditemukan ${noAsuransi} di BPJS, beberapa data akan disesuaikan berdasarkan database BPJS`);
                        }else{
                            alert(`Nomor ${noAsuransi} tidak ditemukan di BPJS`);
                            $('#man-form-noasuransi').textbox('setValue', '');
                        }
                    },
                    error:function(){
                        $('#man-bpjs-loading').hide();
                    }
                });
            }
        }else{
            $('#man-form-noasuransi').textbox({required:false});
        }
    }
});