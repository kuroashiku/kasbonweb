<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'north',split:false,border:false"
        style="height:95px;background-color:#8ae0ed;padding:5px">
        <div style="margin-top:5px">
            <input id="smt-form-tgmeninggal">
        </div>
        <div style="margin-top:5px">
            <div id="smt-btn-upload"></div>
            <div id="smt-btn-download"></div>
        </div>
        <div id="yan-sumti-dlg"></div>
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
                if(obj.data.kun_smti_tgmeninggal!=null)
                $('#smt-form-tgmeninggal').datebox('setValue',tanggal(obj.data.kun_smti_tgmeninggal))
            }
        })
        var selectedKunRow = $('#yan-grid').datagrid('getSelected');
        if(selectedKunRow.kun_smti_tgmeninggal!=null){
            
            $('#smt-btn-download').linkbutton('enable');
        }
        else{
            $('#smt-btn-download').linkbutton('disable');
        }
    });
    var row = $('#yan-grid').datagrid('getSelected');
    $('#smt-form-tgmeninggal').datebox({
        width:'100%',
        prompt:'Tanggal Meninggal',
        height:24
    });
    $('#smt-btn-upload').linkbutton({
        text:'Upload Tgl',
        height:24,
        iconCls: 'fa fa-file',
        onClick:function() {smtUpload();}
    });
    $('#smt-btn-download').linkbutton({
        text:'Download',
        height:24,
        iconCls: 'fa fa-file',
        onClick:function() {smtLoad();}
    });
    function smtUpload(){
        var selectedKunRow = $('#yan-grid').datagrid('getSelected');
        $.ajax({
            type:'POST',
            data:{
                kun_id:selectedKunRow.kun_id,
                kun_smti_tgmeninggal:$('#smt-form-tgmeninggal').datebox('getValue'),
                db:getDB()
            },
            url:getRestAPI('layanan/savesmti'),
            success:function(retval) {
                var obj=JSON.parse(retval)
                smtDownload(obj.row);
                if(obj.row.kun_smti_tgmeninggal!=null){
                    $('#smt-form-tgmeninggal').datebox('setValue',obj.row.kun_smti_tgmeninggal)
                    $('#smt-btn-download').linkbutton('enable');
                }
                else{
                    $('#smt-btn-download').linkbutton('disable');
                }
            },
        });
    }
    function smtLoad(){
        var rowsekarang=$('#yan-grid').datagrid('getSelected');
        smtDownload(rowsekarang);
    }
    function tanggal(tanggal){
        var date = new Date(tanggal);
        datenow = date.getDate() + '/' + (date.getMonth()+1) + '/' + date.getFullYear();
        return datenow;
    }
    function smtDownload(row) {
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
        if (row) $('#yan-sumti-dlg').dialog({
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
                '&tglmeninggal='+row.kun_smti_tgmeninggal+
                '&tglhariini='+today+'" '+
                'width="100%" height="100%" type="application/pdf">'
        });
    }
    
</script>