<style type="text/css">
    .textbox-readonly .textbox-text {
        background:#fff6c1;
        color:#aaaaaa;
    }
    .datagrid-row {
        height:25px;
    }
    .combobox-item {
        padding:2px 5px 2px 5px;
    }
    .master-mnubtndisabled {
        text-align:center;
        width:100px;
        height:100px;
        background:lightgray;
        display:inline-block;
        color:#fff;
        -moz-border-radius:15px;
        -webkit-border-radius:15px;
        border-radius:15px;
        margin:0 10px 10px 0;
    }
    .master-mnubtn {
        text-align:center;
        width:100px;
        height:100px;
        background:#008ba0;
        display:inline-block;
        color:#fff;
        -moz-border-radius:15px;
        -webkit-border-radius:15px;
        border-radius:15px;
        margin:0 10px 10px 0;
    }
    .master-mnubtn:hover {
        background: #ffe96e;
        color: #000000;
        border-color: transparent;
    }
    .master-icobtn {
        vertical-align:middle;
        font-size:45px;
        line-height:75px;
    }
</style>
<div style="padding:20px;">
    <div id="mas-btn-agama" class="master-mnubtn"
        onclick="openMaster('agama')">
        <div class="fa fa-praying-hands master-icobtn"></div>
        <div id="txt-agama">Agama</div>
    </div>
    <div id="mas-btn-pegawai" class="master-mnubtn"
        onclick="openMaster('pegawai')">
        <div class="fa fa-user-md master-icobtn"></div>
        <div id="txt-pegawai">Pegawai</div>
    </div>
    <div id="mas-btn-jabatan" class="master-mnubtn"
        onclick="openMaster('jabatan')">
        <div class="fa fa-id-badge master-icobtn"></div>
        <div id="txt-jabatan">Jabatan</div>
    </div>
    <div id="mas-btn-edukasi" class="master-mnubtn"
        onclick="openMaster('empstatistik')">
        <div class="fa fa-user-graduate master-icobtn"></div>
        <div id="txt-edukasi">Kualifikasi</div>
    </div>
    <div id="mas-btn-kamar" class="master-mnubtn"
        onclick="openMaster('kamar')">
        <div class="fa fa-bed master-icobtn"></div>
        <div id="txt-kamar">Kamar</div>
    </div>
    <div id="mas-btn-laborat" class="master-mnubtn"
        onclick="openMaster('laborat')">
        <div class="fa fa-flask master-icobtn"></div>
        <div id="txt-laborat">Laborat</div>
    </div>
    <div id="mas-btn-layanan" class="master-mnubtn"
        onclick="openMaster('masteryan')">
        <div class="fa fa-stethoscope master-icobtn"></div>
        <div id="txt-layanan">Layanan</div>
    </div>
    <div id="mas-btn-rujukan" class="master-mnubtn"
        onclick="openMaster('rujukan')">
        <div class="fa fa-hospital-user master-icobtn"></div>
        <div id="txt-rujukan">Rujukan</div>
    </div>
    <div id="mas-btn-tindakan" class="master-mnubtn"
        onclick="openMaster('mastertin')">
        <div class="fa fa-syringe master-icobtn"></div>
        <div id="txt-tindakan">Tindakan</div>
    </div>
    <div id="mas-btn-pembayaran" class="master-mnubtn"
    onclick="openMaster('pembayaran')">
        <div class="fa fa-credit-card master-icobtn"></div>
        <div id="txt-pembayaran">Pembayaran</div>
    </div>
    <div id="mas-btn-penyakit" class="master-mnubtn"
        onclick="openMaster('masterkit')">
        <div class="fa fa-virus master-icobtn"></div>
        <div id="txt-penyakit">Penyakit</div>
    </div>
    <div id="mas-btn-obat" class="master-mnubtn"
        onclick="openMaster('masterobat')">
        <div class="fa fa-pills master-icobtn"></div>
        <div id="txt-obat">Obat</div>
    </div>
    <div id="mas-btn-supplier" class="master-mnubtn"
        onclick="openMaster('supplier')">
        <div class="fa fa-truck master-icobtn"></div>
        <div id="txt-supplier">Supplier</div>
    </div>
    <div id="mas-dlg"></div>
</div>
<script type="text/javascript">
function openMaster(master) {
    var url = 'master/view?namamaster='+master;
    var width = 700;
    var height = 340;
    var title = master[0].toUpperCase()+master.substring(1);
    switch(master) {
        case 'agama':
            width = 170;
            height = 200;
            break;
        case 'kamar':
            width = 580;
            break;
        case 'rujukan':
            width = 150;
            height = 260;
            break;
        case 'pembayaran':
            width = 280;
            height = 200;
            break;
        case 'laborat':
            url = 'public/laborat.html';
            break;
        case 'masteryan':
            width = 430;
            title = 'Layanan';
            break;
        case 'jabatan':
            width = 580;
            break;
        case 'pegawai':
            url = 'master/view?namamaster=sdm';
            break;
        case 'masterkit':
            url = 'master/view?namamaster=masterkit';
            title = 'Penyakit';
            break;
        case 'masterobat':
            url = 'master/view?namamaster=masterobat';
            title = 'Obat';
            break;
        case 'mastertin':
            globalConfig.lok_jenis=='1'?url = 'master/view?namamaster=mastertindks':url ='master/view?namamaster=mastertin';
            title = 'Tindakan';
            break;
        case 'empstatistik':
            title = 'Kualifikasi Pendidikan SDM';
    }
    $('#mas-dlg').dialog({
        title:'Master '+title,
        width:width,
        height:height,
        closable:true,
        border:true,
        modal:true,
        resizable:true,
        maximizable:true,
        href:url
    });
}

$(function() {
    if(globalConfig.login_data.lang == 1) {
        $('#txt-agama').html('Religion');
        $('#txt-pegawai').html('Employee');
        $('#txt-jabatan').html('Position');
        $('#txt-edukasi').html('Qualification');
        $('#txt-kamar').html('Room');
        $('#txt-laborat').html('Laboratory');
        $('#txt-layanan').html('Service');
        $('#txt-rujukan').html('Reference');
        $('#txt-tindakan').html('Action');
        $('#txt-pembayaran').html('Payment');
        $('#txt-penyakit').html('Disease');
        $('#txt-obat').html('Drug');
        $('#txt-supplier').html('Supplier');
    }
});
</script>