<script type="text/javascript" src="<?= base_url('js/custom/kunppi_view.js') ?>"></script>
<div class="easyui-layout"
    data-options="fit:true,border:true,animate:false">
    <div data-options="region:'west',border:false"
        style="width:290px">
        <div id="kpi-grid"></div>
        <div id="kpi-grid-tb" style="padding:5px">
            <div id="kpi-btn-add"></div>
            <div id="kpi-btn-save"></div>
            <div id="kpi-btn-cancel"></div>
            <div id="kpi-btn-del"></div>
        </div>
    </div>
    <div data-options="region:'center',split:false,border:false"
        style="background-color:#8ae0ed;padding:10px">
        <div style="padding-bottom:4px"><input id="kpi-form-id"></div>
        <div style="padding-bottom:4px"><input id="kpi-form-tanggal"></div>

        <div style="padding-bottom:4px"><input id="kpi-form-kateter">&ensp;
        Penggunaan alat infus <input id="kpi-form-infus"></div>
        <div style="padding-bottom:4px"><input id="kpi-form-plebitis"></div>
        <div style="padding-bottom:4px"><input id="kpi-form-postophari"></div>
        <div style="padding-bottom:4px"><input id="kpi-form-postopinfeksi">&ensp;
        Tirah Baring <input id="kpi-form-tirahbaring">&ensp; 
        HAP <input id="kpi-form-hap"></div>
        <div style="padding-bottom:4px"><input id="kpi-form-hasilkultur"></div>
        <div style="padding-bottom:4px"><input id="kpi-form-gunaantibiotik"></div>
        <div style="padding-bottom:4px"><input id="kpi-form-klasinfeksi"></div>
        <div style="padding-bottom:4px"><input id="kpi-form-diagnosa"></div>
        <div style="padding-bottom:4px"><input id="kpi-form-ruangan"></div>
        <div style="padding-bottom:4px"><input id="kpi-form-user"></div>
    </div>
</div>