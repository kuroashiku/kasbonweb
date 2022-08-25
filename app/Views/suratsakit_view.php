<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'north',split:false,border:false"
        style="height:95px;background-color:#8ae0ed;padding:5px">
        <div style="margin-top:5px">
            <input id="skt-form-lamacuti">
        </div>
        <div style="margin-top:5px">
            <div id="skt-btn-upload"></div>
            <div id="skt-btn-download"></div>
        </div>
        <div id="yan-suketskt-dlg"></div>
    </div>
</div>
<script type="text/javascript">
    $( document ).ready(function() {
        var selectedKunRow = $('#yan-grid').datagrid('getSelected');
        $.ajax({
            type:'POST',
            data:{
                kun_id:selectedKunRow.kun_id,
                lok_id:globalConfig.login_data?globalConfig.login_data.lok_id:null,
                db:getDB()
            },
            url:getRestAPI('kunjungan/readone'),
            success:function(retval) {
                var obj=JSON.parse(retval)
                if(obj.data.kun_sskt_lamacuti!=null)
                $('#skt-form-lamacuti').numberbox('setValue',obj.data.kun_sskt_lamacuti)
            }
        })
        var selectedKunRow = $('#yan-grid').datagrid('getSelected');
        if(selectedKunRow.kun_sskt_lamacuti!=null){
            
            $('#skt-btn-download').linkbutton('enable');
        }
        else{
            $('#skt-btn-download').linkbutton('disable');
        }
    });
    var row = $('#yan-grid').datagrid('getSelected');
    $('#skt-form-lamacuti').numberbox({
        width:'100%',
        prompt:'Berapa hari cuti',
        height:24
    });
    $('#skt-btn-upload').linkbutton({
        text:'Upload Hari',
        height:24,
        iconCls: 'fa fa-file',
        onClick:function() {sktUpload();}
    });
    $('#skt-btn-download').linkbutton({
        text:'Download',
        height:24,
        iconCls: 'fa fa-file',
        onClick:function() {sktLoad();}
    });
    function sktUpload(){
        var selectedKunRow = $('#yan-grid').datagrid('getSelected');
        $.ajax({
            type:'POST',
            data:{
                kun_id:selectedKunRow.kun_id,
                kun_sskt_lamacuti:$('#skt-form-lamacuti').numberbox('getValue'),
                db:getDB()
            },
            url:getRestAPI('layanan/savesskt'),
            success:function(retval) {
                var obj=JSON.parse(retval)
                sktDownload(obj.row);
                if(obj.row.kun_sskt_lamacuti!=null){
                    $('#skt-form-lamacuti').numberbox('setValue',obj.row.kun_sskt_lamacuti)
                    $('#skt-btn-download').linkbutton('enable');
                }
                else{
                    $('#skt-btn-download').linkbutton('disable');
                }
            },
        });
    }
    function sktLoad(){
        var rowsekarang=$('#yan-grid').datagrid('getSelected');
        sktDownload(rowsekarang);
    }

    function sktDownload(row) {
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
    
</script>