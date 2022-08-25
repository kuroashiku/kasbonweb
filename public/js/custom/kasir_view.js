var kasEditedIdx = -1;
var kasClickedIdx = -1;

$(function() {
    var kasFirstLoad = true;
    $('#kas-filter-lunas').combobox({
        width:100,
        valueField:'id',
        textField:'label',
        editable:false,
        panelHeight:'auto',
        data:[
            {id:'SEMUA', label:'[semua]'},
            {id:'LUNAS', label:'LUNAS'},
            {id:'BELUM', label:'BELUM'}
        ],
        onLoadSuccess:function(data) {
            if (data.length) {
                $(this).combobox('setValue', 'BELUM');
                kasStatusFilterLoaded = true;
            }
        }
    });
    $('#kas-filter-nama').textbox({
        width:90,
        prompt:'Filter nama',
    });
    $('#kas-btn-gofilter').linkbutton({
        text:'Tampilkan',
        iconCls:'fa fa-check-circle',
        onClick:function() {kasReload();}
    });
    $('#kas-btn-notatag').linkbutton({
        text:'Nota tagihan',
        iconCls:'fa fa-file',
        onClick:function() {kasNotaTagihan();}
    });
    $('#kas-btn-kuitansi').linkbutton({
        text:'Kuitansi',
        iconCls:'fa fa-file',
        onClick:function() {kasKuitansi();}
    });
    $('#kas-grid').datagrid({
        border:false,
        singleSelect:true,
        pagination:true,
        pageSize:50,
        toolbar:'#kas-grid-tb',
        idField:'kun_id',
        editorHeight:22,
        autoSave:true,
        fit:true,
        queryParams:{
            kun_statusbayar:'BELUM',
            lok_id:globalConfig.login_data.lok_id,
            db:getDB()
        },
        url:getRestAPI('kunjungan/read_limit'),
        rowStyler:function(index,row) {
            var str = '';
            $.each(globalConfig.kun_status, function(index, obj) {
                if (obj.sta_id == row.kun_status) {
                    str = obj.sta_color;
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
            field:'kun_statusbayar',
            title:'Status bayar',
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
        },{
            field:'kun_keadaanakhir',
            title:'Keadaan akhir',
            resizable:false,
            width:100
        },{
            field:'kun_dokter_sdm_id',
            title:'Dokter',
            formatter:function(value, row) {return row.dokter_sdm_nama;},
            resizable:false,
            width:150
        },{
            field:'man_noasuransi',
            title:'No. BPJS',
            align: 'right',
            formatter:function(value, row) {
                if(row.man_pembayaran && row.man_pembayaran.includes('BPJS')){
                    return row.man_noasuransi;
                }
                return '';
            },
            resizable:false,
            width:150
        }]],
        onSelect:function(index, row) {
            if (row) {
                showOpsiBPJS(row);
                showTagihan(row);
                showPembayaran(row);
            }
        },
        onLoadSuccess:function(data) {
            if (kasFirstLoad) {
                kasFirstLoad = false;
                kasReload();
            }
            else if (data.rows.length) {
                $('#kas-grid').datagrid('selectRow', 0);
                kasClickedIdx = 0;
            }
        },
        onHeaderContextMenu:function(e,field) {
            e.preventDefault();
            if (field=='kun_noregistrasi') {
                var row = $(this).datagrid('getSelected');
                if (row) showID(row.kun_id);
            }
        }
    });

    function showTagihan(row) {
        $('#tag-grid').datagrid('load', {
            kun_id:row.kun_id,
            db:getDB()
        });
    }

    function showPembayaran(row) {
        $('#kby-grid').datagrid('load', {
            kun_id: row.kun_id,
            is_bpjs: row.man_pembayaran && row.man_pembayaran.includes('BPJS'),
            db:getDB()
        });
    }

    function showOpsiBPJS(row){
        if(row.man_pembayaran && row.man_pembayaran.includes('BPJS')){
            $('#kby-btn-bpjs').linkbutton('enable');
        }else{
            $('#kby-btn-bpjs').linkbutton('disable');
        }
    }

    function kasReload() {
        var kun_statusbayar = $('#kas-filter-lunas').combobox('getValue');
        var nama = $('#kas-filter-nama').textbox('getValue');
        $('#kas-grid').datagrid('load', {
            man_nama:nama,
            lok_id:globalConfig.login_data.lok_id,
            kun_statusbayar:kun_statusbayar,
            db:getDB()
        });
    }

    function kasNotaTagihan() {
        var row = $('#kas-grid').datagrid('getSelected');
        $('#kas-rep').remove();
        $('#kas-repdiv').append('<table id="kas-rep" width="100%" height="0%" border="0"></table>');
        printCompanyHeader('kas-rep');
        $('#kas-rep').append('<tr><td style="font-size:20px;font-weight:bold;text-align:center;'+
            'padding-top:10px">NOTA TAGIHAN PASIEN</td></tr>');
        $('#kas-rep').append('<tr><td><table width="100%" border="0" cellspacing="0"><tr>'+
            '<td style="width:50%"><table id="kas-rep-infowest" width="100%"></table></td>'+
            '<td style="width:50%;vertical-align:top">'+
            '<table id="kas-rep-infoeast" width="100%"></table></td>'+
            '</tr></table></td></tr>');
        printInfo('kas-rep-infowest', [
            {label:'No. Registrasi',value:row.kun_noregistrasi},
            {label:'No. RM',value:row.man_norm},
            {label:'Nama',value:row.man_nama},
            {label:'Alamat',value:row.man_alamatktp,nowrap:false}
        ]);
        var today = new Date();
        printInfo('kas-rep-infoeast', [
            {label:'No. Tagihan',value:'TGH-'+row.kun_noregistrasi},
            {label:'Tanggal Tagihan',value:today.toLocaleDateString()},
            {label:'Layanan',value:row.yan_nama},
            {label:'Dokter',value:row.dokter_sdm_nama}
        ]);
        printColumnHeader('kas-rep', [
            {title:'Tanggal'},
            {title:'Nama biaya'},
            {title:'Jumlah',align:'right'}
        ]);
        var data = $('#tag-grid').datagrid('getData');
        var i = 0;
        var stripped = false;
        var curgroup = '';
        var total = 0;
        $.each(data.rows, function(index, row) {
            if (row.group != curgroup) {
                printRow('kas-rep', i++, stripped, [
                    {value:''},
                    {value:row.group},
                    {group:''}
                ], true);
                curgroup = row.group;
                stripped = !stripped;
            }
            printRow('kas-rep', i++, stripped, [
                {value:row.dt},
                {value:row.name},
                {value:parseInt(row.value).toLocaleString(),align:'right'}
            ]);
            total += parseInt(row.value);
            stripped = !stripped;
        });
        printRow('kas-rep', i++, stripped, [
            {value:''},
            {value:'Total'},
            {value:parseInt(total).toLocaleString(),align:'right'}
        ]);
        printSignature('kas-rep', [
            {},{},{title:'Petugas',name:'Sugiono Sumantri',id:'89189001928'}
        ]);
        printDiv('kas-repdiv', 'Nota tagihan');
    }

    function kasKuitansi() {
        var row = $('#kas-grid').datagrid('getSelected');
        $('#kas-rep').remove();
        $('#kas-repdiv').append('<table id="kas-rep" width="100%" height="0%" border="0"></table>');
        printCompanyHeader('kas-rep');
        $('#kas-rep').append('<tr><td style="font-size:20px;font-weight:bold;text-align:center;'+
            'padding-top:10px">KUITANSI PASIEN</td></tr>');
        $('#kas-rep').append('<tr><td><table width="100%" border="0" cellspacing="0"><tr>'+
            '<td style="width:50%"><table id="kas-rep-infowest" width="100%"></table></td>'+
            '<td style="width:50%;vertical-align:top">'+
            '<table id="kas-rep-infoeast" width="100%"></table></td>'+
            '</tr></table></td></tr>');
        printInfo('kas-rep-infowest', [
            {label:'No. Registrasi',value:row.kun_noregistrasi},
            {label:'No. RM',value:row.man_norm},
            {label:'Nama',value:row.man_nama},
            {label:'Alamat',value:row.man_alamatktp,nowrap:false}
        ]);
        var today = new Date();
        printInfo('kas-rep-infoeast', [
            {label:'No. Tagihan',value:'TGH-'+row.kun_noregistrasi},
            {label:'Tanggal Tagihan',value:today.toLocaleDateString()},
            {label:'Layanan',value:row.yan_nama},
            {label:'Dokter',value:row.dokter_sdm_nama}
        ]);
        var data = $('#tag-grid').datagrid('getData');
        var total = 0;
        $.each(data.rows, function(index, r) {
            total += parseInt(r.value);
        });
        $('#kas-rep').append('<tr><td><table id="kas-rep-kuitansi" '+
            'width="100%" border="0" cellspacing="0"></table></td></tr>');
        printInfo('kas-rep-kuitansi', [
            {label:'Telah terima dari',value:row.man_nama},
            {label:'Untuk pembayaran perawatan',value:'Nomor registrasi: '+row.kun_noregistrasi},
            {label:'Jumlah',value:'Rp.'+total.toLocaleString()},
            {label:'Terbilang',value:terbilang(total)}
        ]);
        printSignature('kas-rep', [
            {},{},{title:'Petugas',name:'Sugiono Sumantri',id:'89189001928'}
        ]);
        printDiv('kas-repdiv', 'Nota tagihan');
    }
});
