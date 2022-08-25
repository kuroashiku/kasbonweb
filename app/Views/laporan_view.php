<div class="easyui-layout" fit="true">
    <div data-options="region:'north',split:false,border:false"
        style="height:42px;padding:5px 6px">
        <select id="selectlaporan" class="easyui-combobox" name="state"
            data-options="prompt:'Pilih laporan',editable:false" style="width:50%;">
            <optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LAPORAN-LAPORAN RAWAT INAP">
                <option value="cetak_asesmen_ulang_nyeri">Laporan Asesmen Ulang Nyeri</option>
                <option value="catatan_pemberian_obat_pasien_rawat_inap">Laporan Catatan Pemberian Obat Pasien Rawat Inap</option>
                <option value="catatan_perkembangan_pasien_terintegrasi">Laporan Catatan Perkembangan Pasien Intergrasi</option>
                <option value="cetak_diagnosa_dan_keperawatan_halaman_depan">Laporan Diagnosa dan Rencana Keperawatan</option>
                <option value="cetak_edukasi_pasien">Laporan Formulir Edukasi Pasien dan Keluarga Integrasi</option>
                <option value="cetak_persetujuan_umum_untuk_menerima_pelayanan_kesehatan/cetak_hak_pasien">Laporan Persetujuan Umum Untuk Menerima Pelayanan Kesehatan</option>
                <option value="cetak_identitas">Laporan Identitas Pasien</option>
                <option value="cetak_kajian_awal_medis">Laporan Kajian Awal Medis</option>
                <option value="cetak_komunikasi_antar_unit_pelayanan">Laporan Komunikasi Antar Unit Pelayanan</option>
                <option value="cetak_lembar_hasil_pemeriksaan_penunjang">Laporan Lembar Hasil Pemeriksaan Penunjang</option>
                <option value="cetak_lembar_konfirmasi/cetak_lembar_konfirmasi_belakang">Laporan Lembar Konfirmasi</option>
                <option value="cetak_lembar_konsultasi/cetak_lembar_konfirmasi_belakang">Laporan Lembar Konsultasi</option>
                <option value="cetak_monitor_masalah_harian">Laporan Monitor Masalah Harian Pasien</option>
                <option value="cetak_penempelan_surat_surat">Laporan Penempelan Surat Surat</option>
                <option value="cetak_pengkajian_awal_keperawatan_rawat_inap">Laporan Pengkajian Awal Surat Inap</option>
                <option value="cetak_pengkajian_gizi">Laporan Pengkajian Gizi</option>
                <option value="cetak_pengkajian_pasien_jatuh_khusus_dewasa/cetak_pengkajian_pasien_jatuh_khusus_dewasa_belakang">Laporan Pengkajian Pasien Jatuh Khusus Dewasa</option>
                <option value="cetak_rencana_awal_gizi">Laporan Rencana Awal Gizi</option>
                <option value="cetak_rencana_awal_medis">Laporan Rencana Awal Medis</option>
                <option value="cetak_resume_gizi">Laporan Resume Gizi</option>
                <option value="cetak_resume_perawatan">Laporan Resume Keperawatan</option>
                <option value="cetak_ringkasan_pasien_pulang">Laporan Ringkasan Pasien Pulang</option>
                <option value="cetak_skrining_gizi_pasien_dewasa">Laporan Skrining Gizi Pasien</option>
            </optgroup>
            <optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LAPORAN-LAPORAN RAWAT INAP KANDUNGAN">
                <option value=""></option>
                <option value="cetak_catatan_persalinani">Laporan Catatan Persalinan</option>
                <option value="cetak_lembar_observasi">Laporan Lembar Observasi</option>
                <option value="cetak_partograf">Laporan Partograf</option>
                <option value="cetak_rencana_asuhan_kebidanan_depan/cetak_rencana_asuhan_kebidanan_belakang">Laporan Rencana Awal Asuhan Kebidanan</option>
                <option value="cetak_status_kandungan">Laporan Status Kandungan</option>
                <option value="">Laporan Status Kebidanan</option>
            </optgroup>
        </select>
        <!--&nbsp;<button class="easyui-linkbutton" onclick="downloadFiles()">Download</button>-->
    </div>
    <div data-options="region:'center',title:'Preview'" style="padding:5px;background:#eee;">
        <div id='rep-panel'></div>
        <div id='rep-panel2'></div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $("#selectlaporan").combobox({
            onChange:function() {
                $('#rep-panel').empty();
                $('#rep-panel2').empty();
                var strnilai = String(this.value);
                if (this.value!="") {
                    if (strnilai.search("/")==-1)
                        $('#rep-panel').html('<embed src="https://jobs.reendoo.com/rsbagus/report/'+
                            this.value+'.php?varnorm=00322005" width="100%" height="100%">');
                    else {
                        var str=strnilai.split('/');
                        $('#rep-panel').html('<embed src="https://jobs.reendoo.com/rsbagus/report/'+
                            str[0]+'.php?varnorm=00322005" width="100%" height="100%">');
                        $('#rep-panel2').html('<embed src="https://jobs.reendoo.com/rsbagus/report/'+
                            str[1]+'.php?varnorm=00322005" width="100%" height="100%">');
                    }
                }
            }
        });
    });

    function downloadFiles() {
        var valuecombobox=$("#selectlaporan").val();
        var strcombobox=String(valuecombobox);
        if(valuecombobox!="") {
            if(strcombobox.search("/")==-1) {
                var files = [];
                files.push('https://jobs.reendoo.com/rsbagus/report/'+
                    valuecombobox+'.php?varnorm=00322005');
            }
            else {
                var strcombo=strcombobox.split('/');
                var files = [];
                files.push('https://jobs.reendoo.com/rsbagus/report/'+
                    strcombo[0]+'.php?varnorm=00322005');
                files.push('https://jobs.reendoo.com/rsbagus/report/'+
                    strcombo[1]+'.php?varnorm=00322005');
            }
        }
        for(var ii=0; ii<files.length; ii++) {
            downloadURL(files[ii]);
        }
    }

    var count=0;
    var downloadURL = function downloadURL(url){
        var hiddenIFrameID = 'hiddenDownloader' + count++;
        var iframe = document.createElement('iframe');
        iframe.id = hiddenIFrameID;
        iframe.style.display = 'none';
        document.body.appendChild(iframe);
        iframe.src = url;
    }
</script>
