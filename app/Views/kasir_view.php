<script type="text/javascript" src="<?= base_url('js/custom/kasir_view.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('js/custom/tagihan_view.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('js/custom/kunbayar_view.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('js/custom/utility.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('js/custom/terbilang.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('css/style-man.css') ?>">
<div class="easyui-layout"
    data-options="fit:true,border:false,animate:false">
    <div data-options="region:'center',border:false">
        <div class="easyui-layout"
            data-options="fit:true,border:false,animate:false"
            style="padding:0 1px 0 0;background-color:#95b8e7">
            <div data-options="region:'center',
                split:false,
                border:false"
                title="Detail tagihan (hubungi bagian layanan jika ada biaya yang salah atau belum masuk)"
                style="padding:0 0 1px 0;background-color:#95b8e7">
                <div id="tag-grid"></div>
            </div>
            <div data-options="region:'south',
                split:false,
                animate:false,
                hideCollapsedContent:false,
                border:false"
                title="Daftar kunjungan"
                style="height:300px;padding:0">
                <div id="kas-grid"></div>
                <div id="kas-grid-tb" style="padding:4px 5px 4px 5px">
                    Status bayar&nbsp;&nbsp;<input id="kas-filter-lunas">&nbsp;
                    <input id="kas-filter-nama">&nbsp;
                    <div id="kas-btn-gofilter"></div>&nbsp;
                    <div id="kas-btn-notatag"></div>&nbsp;
                    <div id="kas-btn-kuitansi"></div>
                </div>
            </div>
        </div>
    </div>
    <div data-options="region:'east',
        split:false,
        animate:false,
        hideCollapsedContent:false,
        border:false"
        title="Pembayaran"
        style="width:400px;padding:0">
        <div id="kby-grid"></div>
        <div id="kby-grid-tb" style="padding:5px">
            <div id="kby-btn-save"></div>
            <div id="kby-btn-cancel"></div>
            <div id="kby-btn-bpjs" ></div>
        </div>
    </div>
    <div id="kas-repdiv"></div>
    <div id="kas-bpjs-dlg"></div>
    
</div>