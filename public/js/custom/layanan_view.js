var yanEditedField = null;
$(function() {
    var yanFirstLoad = true;
    $('#yan-filter-layanan').combobox({
        width:200,
        height:24,
        valueField:'yan_id',
        textField:'yan_label',
        showItemIcon:true,
        editable:false,
        panelHeight:'auto',
        onBeforeLoad:function(param) {
            var data = [];
            data.push({yan_id:0,yan_label:'[semua layanan]'});
            for(var i=0;i<globalConfig.layanan.length;i++)
                data.push(globalConfig.layanan[i]);
            $(this).combobox('loadData', data);
            if (globalConfig.login_data.layanan != 'all') {
                $(this).combobox('setValue', globalConfig.login_data.layanan);
                $(this).combobox('readonly', true);
            }
        },
        onLoadSuccess:function(data) {
            if (data.length) {
                $(this).combobox('setValue', 0);
                yanLayananFilterLoaded = true;
            }
        }
    });
    var filterStatus = [{
        id:'SEMUA',
        label:'[semua status]'
    }];
    $.each(globalConfig.kun_status, function(index, obj) {
        filterStatus.push({
            id:obj.sta_id,
            label:obj.sta_nama
        });
    });
    $('#yan-filter-sta').combobox({
        width:120,
        height:24,
        valueField:'id',
        textField:'label',
        editable:false,
        panelHeight:'auto',
        formatter:function(row) {
            var str = row.label;
            $.each(globalConfig.kun_status, function(index, obj) {
                if (obj.sta_id == row.id) {
                    var style = 'color:'+obj.sta_color;
                    if (obj.sta_bold == 1)
                        style += ';font-weight:bold';
                    str = '<span style="'+style+'">'+obj.sta_nama+'</span>';
                    return;
                }
            });
            return str;
        },
        data:filterStatus,
        onChange:function() {
            var id = $(this).combobox('getValue');
            var color = 'black';
            var font = 'normal';
            $.each(globalConfig.kun_status, function(index, obj) {
                if (obj.sta_id == id) {
                    color = obj.sta_color;
                    if (obj.sta_bold == 1) font = 'bold';
                    return;
                }
            });
            $(this).textbox('textbox').css('color', color);
            $(this).textbox('textbox').css('font-weight', font);
        },
        onLoadSuccess:function(data) {
            if (data.length) {
                $(this).combobox('setValue', 'SEMUA');
                yanStatusFilterLoaded = true;
            }
        }
    });
    $('#yan-filter-nama').textbox({
        width:90,
        prompt:'Filter nama',
        height:24
    });
    $('#yan-btn-gofilter').linkbutton({
        iconCls: 'fa fa-play-circle',
        height:24,
        onClick:function() {yanReload();}
    });
    $('#yan-btn-suketmti').linkbutton({
        text:'SMTI',
        height:24,
        iconCls: 'fa fa-file',
        onClick:function() {yanSmti();}
    });
    $('#yan-btn-suketskt').linkbutton({
        text:'SSKT',
        height:24,
        iconCls: 'fa fa-file',
        onClick:function() {yanSuketSkt();}
    });
    $('#yan-btn-suketsht').linkbutton({
        text:'SSHT',
        height:24,
        iconCls: 'fa fa-file',
        onClick:function() {yanSuketSht();}
    });
    $('#yan-btn-medikal').linkbutton({
        text:'RM',
        height:24,
        iconCls:'fa fa-file',
        onClick:function() {yanMedikal();}
    });
    $('#yan-btn-lab').linkbutton({
        text:'Lab',
        height:24,
        iconCls:'fa fa-file',
        onClick:function() {yanLab();}
    });
    $('#yan-btn-foto').linkbutton({
        text:'Foto',
        height:24,
        iconCls:'fa fa-images',
        onClick:function() {yanFoto();}
    });
    $('#yan-btn-ppi').linkbutton({
        text:'PPI',
        height:24,
        iconCls:'fa fa-file',
        onClick:function() {yanPpi();}
    });
    $('#yan-btn-mti').linkbutton({
        text:'SMTI',
        height:24,
        iconCls: 'fa fa-file',
        onClick:function() {yanSmti();}
    });
    $('#yan-btn-skt').linkbutton({
        text:'SSKT',
        height:24,
        iconCls: 'fa fa-file',
        onClick:function() {yanSskt();}
    });
    $('#yan-btn-clb').linkbutton({
        text:'Print Jumlah Lab',
        height:24,
        iconCls: 'fa fa-file',
        onClick:function() {yanPrintLab();}
    });
    $('#yan-grid').datagrid({
        border:false,
        singleSelect:true,
        pagination:true,
        pageSize:50,
        toolbar:'#yan-grid-tb',
        idField:'kun_id',
        editorHeight:22,
        autoSave:true,
        fit:true,
        clickToEdit:false,
        dblclickToEdit:true,
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
            hidden:true,
            width:100
        },{
            field:'kun_noregistrasi',
            title:'No. registrasi',
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
            title:'Pasien hewan',
            formatter:function(value, row) {return row.hwn_nama;},
            resizable:false,
            width:150,
            hidden:globalConfig.lok_jenis != 2
        },{
            field:globalConfig.lok_jenis==2?'hwn_tglahir':'man_tglahir',
            title:'Tgl lahir',
            resizable:false,
            width:85
        },{
            field:globalConfig.lok_jenis==2?'hwn_umur':'man_umur',
            title:'Umur',
            resizable:false,
            width:140
        },{
            field:'kun_yan_id',
            title:'Layanan (Klinik)',
            formatter:function(value, row) {return row.yan_nama;},
            resizable:false,
            width:200
        },{
            field:'kun_tgcheckin',
            title:'Tgl masuk',
            resizable:false,
            width:90
        },{
            field:'kun_tgcheckout',
            title:'Tgl keluar',
            resizable:false,
            width:90
        },{
            field:'kun_noantrian',
            title:'No. antrian',
            resizable:false,
            width:90
        },{
            field:'kun_status',
            title:'<span class="fa fa-edit" style="color:blue" '+
                'title="Bisa diedit dengan double click'+
                '\nENTER untuk Simpan dan ESC untuk Batal"></span> Status',
            resizable:false,
            width:120,
            editor:{
                type:'combobox',
                options:{
                    valueField:'sta_id',
                    textField:'sta_nama',
                    data:globalConfig.kun_status,
                    panelHeight:'auto',
                    editable:false,
                    required:false,
                    formatter:function(row) {
                        var str = row.sta_nama;
                        $.each(globalConfig.kun_status, function(index, obj) {
                            if (obj.sta_id == row.sta_id) {
                                var style = 'color:'+obj.sta_color;
                                if (obj.sta_bold == 1)
                                    style += ';font-weight:bold';
                                str = '<span style="'+style+'">'+obj.sta_nama+'</span>';
                                return;
                            }
                        });
                        return str;
                    },
                    onChange:function() {
                        var id = $(this).combobox('getValue');
                        var color = 'black';
                        var font = 'normal';
                        $.each(globalConfig.kun_status, function(index, obj) {
                            if (obj.sta_id == id) {
                                color = obj.sta_color;
                                if (obj.sta_bold == 1) font = 'bold';
                                return;
                            }
                        });
                        $(this).textbox('textbox').css('color', color);
                        $(this).textbox('textbox').css('font-weight', font);
                    }
                }
            }
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
        },{
            field:'kun_keadaanakhir',
            title:'<span class="fa fa-edit" style="color:blue" '+
                'title="Bisa diedit dengan double click'+
                '\nENTER untuk Simpan dan ESC untuk Batal"></span> Keadaan akhir',
            resizable:false,
            width:250,
            editor:{
                type:'combobox',
                options:{
                    valueField:'kea_id',
                    textField:'kea_nama',
                    url:getRestAPI('master/keadaanakhir'), // tidak baca database tapi json di model
                    panelHeight:'auto',
                    editable:false,
                    required:false
                }
            }
        },{
            field:'kun_dokter_sdm_id',
            title:'<span class="fa fa-edit" style="color:blue" '+
                'title="Bisa diedit dengan double click'+
                '\nENTER untuk Simpan dan ESC untuk Batal"></span> '+
                (globalConfig.lok_jenis==2?'Dokter hewan':'Dokter/DPJP'),
            resizable:false,
            width:150,
            formatter:function(value,row) {
                return row.dokter_sdm_nama;
            },
            editor:{
                type:'combobox',
                options:{
                    valueField:'sdm_id',
                    textField:'sdm_nama',
                    panelMaxHeight:(2+12+2)*10, // 2->padding, 12->font-height, 10->jumlh item maks 
                    editable:false,
                    required:false,
                    onSelect:function(record) {
                        var color = 'black';
                        var decor = '';
                        if (record.invalid != undefined && record.invalid) {
                            color = 'red';
                            decor = 'line-through';
                            $(this).textbox('textbox').tooltip({
                                content:'Dokter sudah tidak aktif.'+
                                    '<br>Silahkan disesuaikan sendiri'
                            });
                        }
                        else
                            $(this).textbox('textbox').tooltip('destroy');
                        $(this).textbox('textbox').css('color', color);
                        $(this).textbox('textbox').css('text-decoration', decor);
                    }
                }
            }
        }]],
        queryParams:{db:getDB()},
        url:getRestAPI('kunjungan/read'),
        onSelect:function(index, row) {
            if (globalConfig.login_data.kamaronly != undefined &&
                globalConfig.login_data.kamaronly == 1) {
                $('#yan-tab').tabs('select', 'Biaya');
                $('#yan-tab').tabs('getTab', 'Periksa').panel('options').tab.hide();
            }
            if (globalConfig.login_data.kandungan != undefined &&
                globalConfig.login_data.kandungan == 1)
                $('#div-bidan').show();
            else
                $('#div-bidan').hide();
            $('#yan-tab').tabs('getTab', 'Evaluasi Dokter').panel('options').tab.hide();
            $('#yan-tab').tabs('getTab', 'Evaluasi Perawat').panel('options').tab.hide();
            if (row) {
                showInformasiRujukan(row);
                $('#kpr-grid').datagrid('load', {
                    man_id:row.kun_man_id,
                    hwn_id:row.kun_hwn_id,
                    db:getDB()
                });
                $('#kbi-grid').datagrid('load', {
                    man_id:row.kun_man_id,
                    hwn_id:row.kun_hwn_id,
                    db:getDB()
                });
                $('#ked-grid').datagrid('load', {
                    man_id:row.kun_man_id,
                    hwn_id:row.kun_hwn_id,
                    db:getDB()
                });
                $('#kep-grid').datagrid('load', {
                    man_id:row.kun_man_id,
                    hwn_id:row.kun_hwn_id,
                    db:getDB()
                });      
                //Dany: Simpan data yang dibutuhkan ke globalconfig     
                globalConfig.layanan_dipilih = row.yan_kode;
                globalConfig.ids.man_id = row.kun_man_id;
                globalConfig.ids.hwn_id = row.hwn_id;
                $("#kpr-btn-detail").linkbutton({
                    disabled: row.yan_kode == undefined || row.yan_kode == 'UMU'
                });
                //////////////////////////////////////////////////////////////////////////
                // JANGAN DIHAPUS, ini dipakai kalau region north menggunakan title
                // supaya title region terupdate oleh antrian yang dipilih
                // baik saat expand maupun collapse
                //////////////////////////////////////////////////////////////////////////
                var nama = globalConfig.lok_jenis == 2?row.hwn_nama+' ('+row.man_nama+')':row.man_nama;
                var title = 'Data Kunjungan '+row.yan_nama+' : '+
                    nama+' (No. registrasi: '+row.kun_noregistrasi+')';
                $('#yan-layout').layout('panel', 'north').panel('setTitle', title);
                $('#yan-layout').layout('panel', 'expandNorth').panel('setTitle', title);
                //////////////////////////////////////////////////////////////////////////
            }
        },
        onLoadSuccess:function(data) {
            if (yanFirstLoad) {
                yanFirstLoad = false;
                yanReload();
            }
            else if (data.rows.length)
                $('#yan-grid').datagrid('selectRow', 0);
            enableBtnCetak(); 
        },
        onCellEdit:function(index,field,value) {
            var ed = $(this).datagrid('getEditor', {
                index:index,
                field:field
            });
            yanEditedField = field;
            ed.target.textbox('textbox').bind('keydown', function(e) {
                if (e.keyCode == 13) {
                    $('#yan-grid').datagrid('endEdit', index);
                }
                else if (e.keyCode == 27) {
                    $('#yan-grid').datagrid('cancelEdit', index);
                    yanEditedField = null;
                }
            });
            if (field == 'kun_dokter_sdm_id')
                yanLoadDokter(ed.target);
        },
        onEndEdit:function(index,row) {
            if (isDemo()) return;
            var updateURL;
            var data = {kun_id:row.kun_id};
            switch(yanEditedField) {
            case 'kun_status':
                updateURL = getRestAPI('kunjungan/updatestatus');
                data.kun_status = row.kun_status;
                break;
            case 'kun_keadaanakhir':
                updateURL = getRestAPI('kunjungan/updatekeadaanakhir');
                data.kun_keadaanakhir = row.kun_keadaanakhir;
                break;
            case 'kun_dokter_sdm_id':
                updateURL = getRestAPI('kunjungan/updatedpjp'); // DPJP: Dokter Penanggung Jawab Pelayanan
                data.kun_dokter_sdm_id = row.kun_dokter_sdm_id;
                var ed = $(this).datagrid('getEditor', {
                    index:index,
                    field:'kun_dokter_sdm_id'
                });
                row.dokter_sdm_nama = ed.target.combobox('getText');
            }
            data.db = getDB();
            $.ajax({
                type:'POST',
                data:data,
                url:updateURL,
                success:function(retval) {
                    var obj = JSON.parse(retval);
                    if (obj.status == 'success') {
                        $('#yan-grid').datagrid('updateRow', {
                            index:index,
                            row:row
                        });
                    }
                    else
                        $.messager.alert(globalConfig.app_nama, obj.errmsg);
                    yanEditedField = null;
                }
            });
        },
        onHeaderContextMenu:function(e,field) {
            e.preventDefault();
            if (field=='kun_noregistrasi') {
                var row = $(this).datagrid('getSelected');
                if (row) showID(row.kun_id);
            }
        }
    });

    $('#yan-grid').datagrid('enableCellEditing').
        datagrid('disableCellSelecting').
        datagrid('gotoCell', {
        index:0,
        field:'kun_id'
    });

    function yanSuketMti() {
        var row = $('#yan-grid').datagrid('getSelected');
        var today = new Date();
        var tglmti=new Date(row.kun_smti_tgmeninggal);
        var jammti=new Date(row.kun_smti_tgmeninggal);
        var jam=jammti.getHours();
        var jamlebih=jam+1;
        var menit=jammti.getMinutes();
        var menitkurang=60-menit;
        var cetak;
        var month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        today = today.getDate() + ' ' + month[today.getMonth()] + ' ' + today.getFullYear();
        tglmti = tglmti.getDate() + ' ' + month[tglmti.getMonth()] + ' ' + tglmti.getFullYear();
        if(menit==30){
            cetak="setengah "+jam;
        }
        else if(menit<30&&menit>0){
            cetak=jam+" lebih "+menit+" menit";
        }
        else if(menit>30&&menit<=59){
            cetak=jamlebih+" kurang "+menitkurang+" menit";
        }
        else if(menit==0&&jam==0)
        {
            cetak="tepat tengah malam";
        }
        else if(menit==0){
            cetak=jam+" tepat";
        }
        if (row) $('#yan-suketmti-dlg').dialog({
            title:'Cetak Surat Ket Kematian',
            width:500,
            height:350,
            closable:true,
            border:true,
            resizable:true,
            maximizable:true,
            modal:true,
            content:'<embed src="https://jobs.reendoo.com/jasper/cetak_surat_ket_kematian.php'+
                '?noregis='+row.kun_noregistrasi+
                '&umur='+row.man_umur+
                '&nipdokter='+row.dokter_sdm_nip+
                '&namadokter='+row.dokter_sdm_nama+
                '&jammeninggal='+cetak+
                '&tglmeninggal='+tglmti+
                '&tglhariini='+today+'" '+
                'width="100%" height="100%" type="application/pdf">'
        });
    }

    function yanSuketSkt() {
        var row = $('#yan-grid').datagrid('getSelected');
        var today = new Date();
        var month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        var angka = ['satu','dua','tiga','empat','lima','enam','tujuh','delapan','sembilan'];
        var angkabelas = ['se','dua','tiga','empat','lima','enam','tujuh','delapan','sembilan'];
        var angkaterbilang;
        var tesangka=row.kun_sskt_lamacuti.length;
        var splitangka=row.kun_sskt_lamacuti.split('');
        if(tesangka==1){
            angkaterbilang=angka[row.kun_sskt_lamacuti-1];
        }
        else if(tesangka==2){
            if(splitangka[0]==1&&splitangka[1]!=0){
                angkaterbilang=angkabelas[splitangka[1]-1]+" belas";
            }
            else if(splitangka[0]>1&&splitangka[1]!=0){
                angkaterbilang=angkabelas[splitangka[0]-1]+" puluh "+angka[splitangka[1]-1];
            }
            else if(splitangka[0]>1&&splitangka[1]==0){
                angkaterbilang=angkabelas[splitangka[0]-1]+" puluh";
            }
            else if(splitangka[0]==1&&splitangka[1]==0){
                angkaterbilang="sepuluh";
            }
            else if(splitangka[0]==1&&splitangka[1]==1){
                angkaterbilang="sebelas";
            }
        }
        else if(tesangka==3){
            if(splitangka[0]==1&&splitangka[1]==0&&splitangka[2]==0){
                angkaterbilang="seratus";
            }
            else{
                angkaterbilang="ratusan";
            }
        }
        today = today.getDate() + ' ' + month[today.getMonth()] + ' ' + today.getFullYear();
        if (row) $('#yan-suketskt-dlg').dialog({
            title:'Cetak Surat Ket Sakit',
            width:500,
            height:350,
            closable:true,
            border:true,
            modal:true,
            resizable:true,
            maximizable:true,
            content:'<embed src="https://jobs.reendoo.com/jasper/cetak_surat_ket_sakit.php'+
                '?noregis='+row.kun_noregistrasi+
                '&umur='+row.man_umur+
                '&nipdokter='+row.dokter_sdm_nip+
                '&namadokter='+row.dokter_sdm_nama+
                '&cutihariterbilang='+angkaterbilang+
                '&cutihari='+row.kun_sskt_lamacuti+
                '&tglhariini='+today+'" '+
                'width="100%" height="100%" type="application/pdf">'
        });
    }

    function yanSuketSht() {
        var row = $('#yan-grid').datagrid('getSelected');
        var today = new Date();
        var month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        today = today.getDate() + ' ' + month[today.getMonth()] + ' ' + today.getFullYear();
        if (row) $('#yan-suketsht-dlg').dialog({
            title:'Cetak Surat Ket Sehat',
            width:500,
            height:350,
            closable:true,
            border:true,
            modal:true,
            resizable:true,
            maximizable:true,
            content:'<embed src="https://jobs.reendoo.com/jasper/cetak_surat_ket_sehat.php'+
                '?noregis='+row.kun_noregistrasi+
                '&umur='+row.man_umur+
                '&nipdokter='+row.dokter_sdm_nip+
                '&namadokter='+row.dokter_sdm_nama+
                '&tglhariini='+today+'" '+
                'width="100%" height="100%" type="application/pdf">'
        });
    }

    function showFieldRujukan(part, label, value, rightside=true) {
        if (rightside) $(part).append('<tr height="0%"><td style="white-space:nowrap"'+
            'class="rujukan-field-name" width="0%">'+
            label+':&nbsp;</td><td width="100%">'+value+'</td></tr>');
        else $(part).append('<tr height="0%" class="rujukan-field-name"><td>'+
            label+': </td></tr><tr height="0%"><td>'+value+'</td></tr>');
    }

    function showInformasiRujukan(row) {
        $('#kpr-data-rujukan').html('');
        if (row.ru_kun_id) {
            $('#kpr-data-rujukan').append('<tr height="0%"><td style="padding:10px 10px 0 10px">'+
                '<table id="kpr-dataru-1" width="100%" height="0%" style="font-size:14px"'+
                'cellpadding="0" cellspacing="0"></table></td></tr>');
            $('#kpr-data-rujukan').append('<tr height="0%"><td style="padding:10px 10px 0 10px">'+
                '<table id="kpr-dataru-2" width="100%" height="0%" style="font-size:14px"'+
                'cellpadding="0" cellspacing="0"></table></td></tr>');
            $('#kpr-data-rujukan').append('<tr height="0%"><td style="padding:10px 10px 0 10px">'+
                '<table id="kpr-dataru-3" width="100%" height="0%" style="font-size:14px"'+
                'cellpadding="0" cellspacing="0"></table></td></tr>');
            $('#kpr-data-rujukan').append('<tr height="0%"><td style="padding:10px 10px 0 10px">'+
                '<table id="kpr-dataru-4" width="100%" height="0%" style="font-size:14px"'+
                'cellpadding="0" cellspacing="0"></table></td></tr>');
            showFieldRujukan('#kpr-dataru-1', 'No. Register Rujukan', row.ru_nomor);
            showFieldRujukan('#kpr-dataru-1', 'No. Kartu (BPJS/Jaskesda/SPM)', row.kun_noasuransi);
            showFieldRujukan('#kpr-dataru-1', 'No. Reg. Rujukan BPJS (P-Care)', row.ru_nomormitra);
            var d = new Date(row.ru_tanggal);
            showFieldRujukan('#kpr-dataru-1', 'Tanggal', d.toDateString());
            showFieldRujukan('#kpr-dataru-1', 'Jam', d.toLocaleTimeString());
            showFieldRujukan('#kpr-dataru-2', 'Nama', row.man_nama);
            showFieldRujukan('#kpr-dataru-2', 'Gender', row.man_kelamin);
            showFieldRujukan('#kpr-dataru-2', 'Umur', row.man_umur);
            showFieldRujukan('#kpr-dataru-2', 'Alamat', row.man_alamatktp);
            showFieldRujukan('#kpr-dataru-3', 'Anamnesa', row.ru_anamnesa, false);
            showFieldRujukan('#kpr-dataru-3', 'Pem. Fisik', row.ru_cekfisik, false);
            showFieldRujukan('#kpr-dataru-3', 'Pem. Penunjang', row.ru_cekpenunjang, false);
            showFieldRujukan('#kpr-dataru-3', 'Diagnosis (ICD X)', row.kitnama, false);
            showFieldRujukan('#kpr-dataru-3', 'Pengobatan/Tindakan yang telah diberikan', row.tin_nama, false);
            var alasan = '';
            switch(row.ru_alasan) {
                case 'Kompetensi':
                    alasan = 'Diagnosis di luar kompetensi';
                    break;
                case 'Kapasitas':
                    alasan = 'Diagnosis ada dalam kompetensi, namun ada keterbatasan kemampuan dan atau kapasitas';
                    break;
            }
            showFieldRujukan('#kpr-dataru-3', 'Alasan dirujuk', alasan, false);
            showFieldRujukan('#kpr-dataru-4', 'Nama', row.sdm_nama);
            showFieldRujukan('#kpr-dataru-4', 'Pengirim', row.ru_pengirim);
            showFieldRujukan('#kpr-dataru-4', 'Unit', row.ru_unitpengirim);
            showFieldRujukan('#kpr-dataru-4', 'Telpon', row.ru_telponpengirim);
        }
        else $('#kpr-data-rujukan').append('<tr><td style="text-align:center;'+
            'vertical-align:middle;background-color:#326e8a;color:white">'+
            '<span style="font-weight:bold;font-size:30px">Tidak ada data rujukan</span><br>'+
            '<span style="font-size:15px">Pasien datang dengan tipe rujukan<br>'+
            '<b>'+row.kun_rujukan+'</b></span></td></tr>');
    }

    function yanReload() {
        var yan_id = $('#yan-filter-layanan').combobox('getValue');
        var sta = $('#yan-filter-sta').combobox('getValue');
        var nama = $('#yan-filter-nama').textbox('getValue');
        $('#yan-grid').datagrid('load', {
            man_nama:nama,
            lok_id:globalConfig.login_data.lok_id,
            yan_id:yan_id,
            kun_status:sta,
            db:getDB()
        });
    }

    function yanMedikal() {
        var row = $('#yan-grid').datagrid('getSelected');
        $('#yan-rep').remove();
        $('#yan-repdiv').append('<table id="yan-rep" width="100%" height="0%" border="0"></table>');
        printCompanyHeader('yan-rep');
        $('#yan-rep').append('<tr><td style="font-size:20px;font-weight:bold;text-align:center;'+
            'padding-top:10px">MEDICAL REPORT PASIEN</td></tr>');
        $('#yan-rep').append('<tr><td><table width="100%" border="0" cellspacing="0"><tr>'+
            '<td style="width:50%"><table id="yan-rep-infowest" width="100%"></table></td>'+
            '<td style="width:50%;vertical-align:top">'+
            '<table id="yan-rep-infoeast" width="100%"></table></td>'+
            '</tr></table></td></tr>');
        printInfo('yan-rep-infowest', [
            {label:'No. Registrasi',value:row.kun_noregistrasi},
            {label:'No. RM',value:row.man_norm},
            {label:'Nama',value:row.man_nama},
            {label:'Alamat',value:row.man_alamatktp,nowrap:false}
        ]);
        var today = new Date();
        printInfo('yan-rep-infoeast', [
            {label:'No. Registrasi',value:row.kun_noregistrasi},
            {label:'Tanggal Periksa',value:today.toLocaleDateString()},
            {label:'Layanan',value:row.yan_nama},
            {label:'Dokter',value:row.dokter_sdm_nama}
        ]);
        var datakpr = $('#kpr-grid').datagrid('getData');
        var datakbi = $('#kbi-grid').datagrid('getData');
        var urutan=["Pertama","Kedua","Ketiga","Keempat","Kelima","Keenam","Ketujuh","Kedelapan","Kesembilan","Kesepuluh"]
        var i=0, j=0;
        $.each(datakpr.rows, function(index, r) {
            $('#yan-rep').append('<tr><td><table id="yan-rep-medikal-checkup" '+
                'width="100%" cellspacing="0"></table></td></tr>');
                
            printInfoMC('yan-rep-medikal-checkup', [
                {label:'Pemeriksaan '+urutan[i],value1:"",value2:""},
                {label:'&nbsp;   Medical Check Up ',value1:"",value2:""},
                {label:'&nbsp;   ',value1:"Tinggi/Berat badan",value2:":"+r.kpr_tb+"/"+r.kpr_bb},
                {label:'&nbsp;   ',value1:"Pernafasan",value2:":"+r.kpr_nafas+" per menit"},
                {label:'&nbsp;   ',value1:"Tensi",value2:":"+r.kpr_sistolik+"/"+r.kpr_diastolik+" mmHg"},
                {label:'&nbsp;   ',value1:"Suhu",value2:":"+r.kpr_suhu+"<span>&#8451;</span>"},
                {label:'&nbsp;   Anamnesa',value1:"",value2:":"+r.kpr_anamnesa},
                {label:'&nbsp;   Diagnosa',value1:"",value2:":"+r.kpr_diagnosa},
                {label:'&nbsp;   Diagnosa Ilmiah',value1:"",value2:":"+r.kit_nama},
                {label:'&nbsp;   Prognosa',value1:"",value2:":"+r.kpr_prognosa},
                {label:'&nbsp;   Terapi',value1:"",value2:":"+r.kpr_terapi},
            ]);
            i=i+1;
        });
        datakbi.rows.length>1?$('#yan-rep').append('<tr><td style="padding: 3px 6px 3px 6px; font-weight: bold; font-size: 14px;">Detail Medikal</td></tr>'):"";
        $.each(datakbi.rows, function(index, r) {
            $('#yan-rep').append('<tr><td><table id="yan-rep-medikal-pays" '+
                'width="100%" cellspacing="0"></table></td></tr>');
            printInfo('yan-rep-medikal-pays', [
                {label:r.kbi_jns_id=="D"?"&nbsp;   Dokter<br>":
                (r.kbi_jns_id=="O"?"&nbsp;   Obat<br>":
                (r.kbi_jns_id=="I"?"&nbsp;   Injeksi<br>":
                (r.kbi_jns_id=="L"?"&nbsp;   Laborat<br>":
                (r.kbi_jns_id=="T"?"&nbsp;   Tindakan<br>":"Kamar<br>"))))
                ,value:":"+
                r.kbi_jns_id=="D"?"Nama Dokter: "+r.sdm_nama+", <br>Tgl Periksa: "+r.kbi_tanggal:
                (r.kbi_jns_id=="O"?"Nama Obat: "+r.obt_nama+", <br>Kuantiti: "+r.kbi_obt_qty+", <br>Tgl Periksa: "+r.kbi_tanggal:
                (r.kbi_jns_id=="I"?"Nama Injeksi: "+r.obt_nama+", <br>Kuantiti: "+r.kbi_obt_qty+", <br>Tgl Periksa: "+r.kbi_tanggal:
                (r.kbi_jns_id=="L"?"Jenis Pemeriksaan Lab: "+r.lab_nama+", <br>Tgl Periksa: "+r.kbi_tanggal:
                (r.kbi_jns_id=="T"?"Jenis Tindakan: "+r.tin_nama+", <br>Tgl Periksa: "+r.kbi_tanggal:"Nama Kamar:"+r.kmr_nama+", <br>Tgl Rawat Inap: "+r.kbi_tanggal))))}
            ]);
            j=j+1;
        });
        printSignature('yan-rep', [
            {},{},{title:'Petugas',name:row.dokter_sdm_nama,id:row.dokter_sdm_nip}
        ]);
        printDiv('yan-repdiv', 'Medical Report');
    }

    function yanLab() {
        var row = $('#yan-grid').datagrid('getSelected');
        var datakbi = $('#kbi-grid').datagrid('getData');
        $.ajax({
            url:getRestAPI("kunlab/readprint"),
            data:{
                klb_kun_id:datakbi.rows[0].kbi_kun_id,
                db:getDB()
            },
            type:'POST',
            success:function(retval){
                var obj=JSON.parse(retval);
                console.log(obj)
                $('#yan-replab').remove();
                $('#yan-rep').remove();
                $('#yan-replabdiv').append('<table id="yan-replab" width="100%" height="0%" border="0"></table>');
                printCompanyHeader('yan-replab');
                $('#yan-replab').append('<tr><td><table width="100%" cellspacing="0"><tr>'+
                    '<td style="width:50%;"><table id="yan-replab-infowest" width="100%"></table></td>'+
                    '<td style="width:50%;vertical-align:top;">'+
                    '<table id="yan-replab-infoeast" width="100%"></table></td>'+
                    '</tr></table></td></tr>');
                printInfo('yan-replab-infowest', [
                    {label:'No. Registrasi',value:row.kun_noregistrasi},
                    {label:'No. RM',value:row.man_norm},
                    {label:'Nama',value:row.man_nama},
                    {label:'Alamat',value:row.man_alamatktp,nowrap:false}
                ]);
                var today = new Date();
                printInfo('yan-replab-infoeast', [
                    {label:'No. Registrasi',value:row.kun_noregistrasi},
                    {label:'Tanggal Periksa',value:today.toLocaleDateString()},
                    {label:'Layanan',value:row.yan_nama},
                    {label:'Dokter',value:row.dokter_sdm_nama}
                ]);
                if(obj.rows[0]){
                $('#yan-replab').append('<tr><td style="border-top: 1px solid black;font-size:16px;font-weight:bold;'+
                    'padding-top:10px">PARAMETERS &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&ensp; NILAI RUJUKAN</td></tr>');
                }
                $('#yan-replab').append('<tr><td><table width="100%" cellspacing="0"><tr>'+
                    '<td style="width:60%; vertical-align: top;"><table id="yan-replab-medikal-checkup" width="100%"></table></td>'+
                    '<td style="width:40%;vertical-align:top;">'+
                    '<table id="yan-replab-image" width="100%"></table></td>'+
                    '</tr></table></td></tr>');
                
                $.each(obj.rows,function(key,labitem){
                    printInfoLab('yan-replab-medikal-checkup', [{
                        label:labitem.lab_nama,
                        value1:labitem.klb_lab_value,
                        value2:labitem.lab_satuan,
                        value3:labitem.lab_normal
                    }]);
                });
                $.each(obj.foto,function(key,labimage){
                    printInfoLab('yan-replab-image', [{
                        label:'<img src="public/viewfoto.php?id='+labimage.kfo_id+'&rnd='+
                            Math.random()+'" width="150" height="150">',
                        value1:'',
                        value2:'',
                        value3:''
                    }]);
                });
                $.each(datakbi.rows,function(key,kbiitem){
                    if(kbiitem.bea_nama=='Darah Lengkap'){
                        $('#yan-replab-medikal-checkup').append('<tr><td style="font-size:16px;font-weight:bold;'+
                    'padding-top:10px; ">*) NILAI RUJUKAN</td></tr>');
                    $('#yan-replab-medikal-checkup').append('<tr><td colspan="4"><table width="100%" cellspacing="0"><tr>'+
                        '<td style="width:100%;"><table id="yan-replab-west" class="table table-responsive" width="100%" border="1" style="font-size:14px;text-align: center;">'+
                        '<tr><td>Status</td><td>WBC</td><td>RBC</td><td>HGB</td><td>HCT</td></tr>'+
                        '<tr><td>Laki-laki</td><td>4,8-10,8</td><td>4,7-6,1</td><td>14-18</td><td>42-52</td></tr>'+
                        '<tr><td>Perempuan</td><td>4,8-10,8</td><td>4,2-5,4</td><td>12-16</td><td>37-47</td></tr>'+
                        '<tr><td>< 2 Minggu</td><td>10-26</td><td>3,7-6,5</td><td>14,9-23,7</td><td>47-75</td></tr>'+
                        '<tr><td>2 Minggu</td><td>6-21</td><td>3,9-5,9</td><td>13,4-19,8</td><td>41-65</td></tr>'+
                        '<tr><td>2 Bulan</td><td>6-18</td><td>3,1-4,3</td><td>9,4-13</td><td>28-42</td></tr>'+
                        '<tr><td>6 Bulan</td><td>6-17,5</td><td>3,9-5,5</td><td>11,4-14,4</td><td>31-41</td></tr>'+
                        '<tr><td>1 Tahun</td><td>6-17,5</td><td>4,1-5,3</td><td>11,3-14,1</td><td>33-41</td></tr>'+
                        '<tr><td>2-6 Tahun</td><td>6-17</td><td>3,9-5,9</td><td>11,5-13,5</td><td>34-40</td></tr>'+
                        '<tr><td>6-12 Tahun</td><td>4,5-14,5</td><td>4-5,2</td><td>11,5-15,5</td><td>35-45</td></tr>'+
                        '</table></td>'+
                        '<td style="width:50%;vertical-align:top;">'+
                        '<table id="yan-replab-west" width="100%"></table></td>'+
                        '</tr></table></td></tr>');
                    }
                });
                printSignature('yan-replab', [{},{},{
                    title:'Petugas',
                    name:row.dokter_sdm_nama,id:
                    row.dokter_sdm_nip
                }]);
                printDiv('yan-replabdiv', 'Laborat');
            }
        });
    }

    function yanPrintLab(){
        $.ajax({
            url:getRestAPI("kunlab/labreport"),
            data:{
                db:getDB()
            },
            type:'POST',
            success:function(retval){
                var obj=JSON.parse(retval);
                console.log(obj)
                $('#yan-repprintlab').remove();
                $('#yan-rep').remove();
                $('#yan-repprintlabdiv').append('<table id="yan-repprintlab" width="100%" height="0%" border="0"></table>');
                printCompanyHeader('yan-repprintlab');
                $('#yan-repprintlab').append('<tr><td style="text-align: center; border-top: 1px solid black;font-size:16px;font-weight:bold;'+
                    'padding-top:10px; padding-bottom:15px">Laporan Jumlah Tes Laboratorium</td></tr>');
                
                $('#yan-repprintlab').append('<tr><td><table width="100%" cellspacing="0"><tr>'+
                    '<td style="width:100%;"><table id="yan-repprintlab-infowest" width="100%"></table></td>'+
                    '</tr></table></td></tr>');
                $.each(obj.rows,function(key,r){
                    printInfoLab('yan-repprintlab-infowest', [
                        {label:'Nama Tes Lab:',value1:r.lab_nama,value2:"Group Lab:"+r.lab_group,value3:"Jumlah:"+r.jumlah},
                    ]);
                });
                $('#yan-repprintlab').append('<tr><td><table width="100%" cellspacing="0"><tr>'+
                    '<td style="width:60%; vertical-align: top;"><table id="yan-repprintlab-medikal-checkup" width="100%"></table></td>'+
                    '<td style="width:40%;vertical-align:top;">'+
                    '<table id="yan-repprintlab-image" width="100%"></table></td>'+
                    '</tr></table></td></tr>');
                console.log(obj)
            
                printDiv('yan-repprintlabdiv', 'Laborat');
            }
        });
    }

    function yanFoto() {
        $('#yan-foto-dlg').dialog({
            title:'Foto-foto dalam satu kunjungan poli',
            width:700,
            height:400,
            closable:true,
            border:true,
            modal:true,
            resizable:true,
            maximizable:true,
            href:'layanan/foto'
        });
    }

    function yanPpi() {
        $('#yan-ppi-dlg').dialog({
            title:'PPI',
            width:700,
            height:400,
            closable:true,
            border:true,
            modal:true,
            resizable:true,
            maximizable:true,
            href:'layanan/ppi'
        });
    }

    function yanSmti() {
        $('#yan-mti-dlg').dialog({
            title:'Print Surat Kematian',
            width:700,
            height:120,
            closable:true,
            border:true,
            modal:true,
            resizable:true,
            maximizable:true,
            href:'layanan/suketmti'
        });
    }
    function yanSskt() {
        $('#yan-skt-dlg').dialog({
            title:'Print Surat Sakit',
            width:700,
            height:120,
            closable:true,
            border:true,
            modal:true,
            resizable:true,
            maximizable:true,
            href:'layanan/suketskt'
        });
    }

    function yanLoadDokter(cb) {
        var row = $('#yan-grid').datagrid('getSelected');
        $.ajax({
            type:'POST',
            async:false,
            url:getRestAPI('sdm/dokter'),
            data:{
                db:getDB(),
                com_id:globalConfig.com_id
            },
            success:function(retval) {
                var data = JSON.parse(retval);
                var exists = false;
                for(var i=0;i<data.length;i++) {
                    if (data[i].sdm_id == row.kun_dokter_sdm_id) {
                        exists = true;
                        break;
                    }
                }
                if (!exists) {
                    data.push({
                        sdm_id:row.kun_dokter_sdm_id,
                        sdm_nama:row.dokter_sdm_nama,
                        invalid:true
                    });
                }
                cb.combobox('loadData', data);
                cb.combobox('setValue', row.kun_dokter_sdm_id);
            }
        });
    }
});
