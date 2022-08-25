$(function() {
    /*$('#poli-filter-layanan').combobox({
        width:200,
        height:24,
        valueField:'yan_id',
        textField:'yan_nama',
        showItemIcon:true,
        editable:false,
        panelHeight:'auto',
        onBeforeLoad:function(param) {
            var data = [];
            data.push({yan_id:0,yan_nama:'[semua layanan]'});
            for(var i=0;i<globalConfig.layanan.length;i++)
                data.push(globalConfig.layanan[i]);
            $(this).combobox('loadData', data);
        },
        onLoadSuccess:function(data) {
            if (data.length) {
                $(this).combobox('setValue', 0);
                poliLayananFilterLoaded = true;
            }
        }
    });
    $('#poli-filter-sta').combobox({
        width:120,
        height:24,
        valueField:'id',
        textField:'label',
        editable:false,
        panelHeight:'auto',
        data:[
            {id:'SEMUA',    label:'[semua]'},
            {id:'ANTRI',    label:'ANTRI'},
            {id:'DILAYANI', label:'DILAYANI'},
            {id:'MENGINAP', label:'MENGINAP'},
            {id:'BATAL',    label:'BATAL'},
            {id:'SELESAI',  label:'SELESAI'}
        ],
        onLoadSuccess:function(data) {
            if (data.length) {
                $(this).combobox('setValue', 'SEMUA');
                poliStatusFilterLoaded = true;
            }
        }
    });
    $('#poli-btn-gofilter').linkbutton({
        height:24,
        onClick:function() {poliReload();}
    });*/
    $('#accrep-grid').datagrid({
        border:false,
        singleSelect:true,
        pagination:true,
        //toolbar:'#poli-grid-tb',
        idField:'kun_id',
        pageSize:50,
        fit:true,
        rowStyler:function(index,row) {
            var str = '';
            switch(row.kun_status) {
                case 'ANTRI'   :str = 'color:#ff0000;font-weight:bold';break;
                case 'DILAYANI':str = 'color:#0060a4';break;
                case 'MENGINAP':str = 'color:#00b81e';break;
                case 'BATAL'   :str = 'color:#969696';break;
                case 'SELESAI' :str = 'color:#000000';
            }
            return str;
        },
        columns:[[{
            field:'kun_id',
            title:'ID',
            resizable:false,
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
            field:'kun_yan_id',
            title:'Layanan',
            formatter:function(value, row) {return row.yan_nama;},
            resizable:false,
            width:150
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
            title:'Status',
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
            db:getDB()
        },
        url:getRestAPI('kunjungan/read'),
        onSelect:function(index, row) {
            if (row) {
                showInformasiRujukan(row);
                $('#kpr-grid').datagrid('load', {
                    man_id:row.kun_man_id,
                    hwn_id:row.kun_hwn_id
                });
                $('#kbi-grid').datagrid('load', {
                    man_id:row.kun_man_id,
                    hwn_id:row.kun_hwn_id
                });
                // JANGAN DIHAPUS, ini dipakai kalau region north menggunakan title
                // supaya title region terupdate oleh antrian yang dipilih
                // baik saat expand maupun collapse
                
                var title = 'Data Kunjungan '+row.yan_nama+' : '+
                    row.man_nama+' (No. registrasi: '+row.kun_noregistrasi+')';
                $('#poli-layout').layout('panel', 'north').panel('setTitle', title);
                $('#poli-layout').layout('panel', 'expandNorth').panel('setTitle', title);
            }
        },
        onLoadSuccess:function(data) {
            var pager = $(this).datagrid('getPager');
            pager.pagination({
                buttons:$('#poli-grid-tool')
            });
            if (poliFirstLoad) {
                poliFirstLoad = false;
                poliReload();
            }
        }
    });

    function showInformasiRujukan(row) {
        $('#poli-static-rusukno').html(row.ru_nomor);
        $('#poli-static-noasuransi').html(row.kun_noasuransi);
        $('#poli-static-rusuknomitra').html(row.ru_nomormitra);
        var d = new Date(row.ru_tanggal);
        $('#poli-static-rusuktg').html(d.toDateString());
        $('#poli-static-rusuktm').html(d.toLocaleTimeString());
        $('#poli-static-manusia').html(row.man_nama);
        $('#poli-static-gender').html(row.man_kelamin);
        $('#poli-static-umur').html(row.man_umur);
        $('#poli-static-alamat').html(row.man_alamatktp);
        $('#poli-static-rusukanamnesa').html(row.ru_anamnesa);
        $('#poli-static-rusukcekfisik').html(row.ru_cekfisik);
        $('#poli-static-rusukcekpenunjang').html(row.ru_cekpenunjang);
        $('#poli-static-rusukpenyakit').html(row.kit_nama);
        $('#poli-static-rusuktindakan').html(row.tin_nama);
        var alasan = '';
        switch(row.ru_alasan) {
            case 'Kompetensi':
                alasan = 'Diagnosis di luar kompetensi';
                break;
            case 'Kapasitas':
                alasan = 'Diagnosis ada dalam kompetensi, namun ada keterbatasan kemampuan dan atau kapasitas';
                break;
        }
        $('#poli-static-rusukalasan').html(alasan);
        $('#poli-static-rusukpenerima').html(row.sdm_nama);
        $('#poli-static-rusukpengirim').html(row.ru_pengirim);
        $('#poli-static-rusukunitpengirim').html(row.ru_unitpengirim);
        $('#poli-static-rusuktelponpengirim').html(row.ru_telponpengirim);
    }

    function poliReload() {
        var yan_id = $('#poli-filter-layanan').combobox('getValue');
        var sta = $('#poli-filter-sta').combobox('getValue');
        $('#poli-grid').datagrid('load', {
            yan_id:yan_id,
            status:sta,
            db:getDB()
        });
    }
});
