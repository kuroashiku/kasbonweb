<script type="text/javascript" src="<?= base_url('js/custom/layanan_view.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('js/custom/kunperiksa_view.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('js/custom/kunbiaya_view.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('js/custom/kunevaldok_view.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('js/custom/kunevalprw_view.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('js/custom/kunlab_view.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('js/custom/utility.js') ?>"></script>
<div class="easyui-layout"
    data-options="fit:true,border:false,animate:false">
    <div data-options="region:'center',border:false">
        <div class="easyui-layout"
            data-options="fit:true,border:false,animate:false"
            id="yan-layout">
            <div data-options="region:'north',
                collapsed:false,
                hideCollapsedContent:false,
                animation:false,
                split:false,
                border:false"
                title="Daftar Kunjungan"
                style="height:45%;padding:0 0 1px 0;background-color:#95b8e7">
                <div id="yan-grid"></div>
                <div id="yan-grid-tb" style="padding:3px 5px 3px 5px">
                    <input id="yan-filter-layanan">&nbsp;
                    <input id="yan-filter-sta">
                    <input id="yan-filter-nama">
                    <div id="yan-btn-gofilter"></div>
                    <div id="yan-btn-mti"
                        title="Cetak Surat Keterangan Kematian"
                        class="easyui-tooltip"></div>
                    <div id="yan-btn-skt"
                        title="Cetak Surat Keterangan Sakit"
                        class="easyui-tooltip"></div>
                    <div id="yan-btn-suketsht"
                        title="Cetak Surat Keterangan Sehat"
                        class="easyui-tooltip"></div>
                    <div id="yan-btn-medikal"
                        title="Cetak Laporan Rekam Medis"
                        class="easyui-tooltip"></div>
                    <div id="yan-btn-lab"
                        title="Cetak Laporan Laboratorium"
                        class="easyui-tooltip"></div>
                    <div id="yan-btn-foto"></div>
                    <div id="yan-btn-ppi"></div>
                    <div id="yan-btn-clb"></div>
                </div>
            </div>
            <div data-options="region:'center',iconCls:'icon-edit',border:false">
                <div class="easyui-tabs" id="yan-tab"
                    data-options="border:false,fit:true,tabPosition:'bottom'"
                    style="padding:0px">
                    <div title="Periksa" style="padding:0px">
                        <div class="easyui-layout"
                            data-options="fit:true,border:true,animate:false">
                            <div data-options="region:'west',border:false"
                                style="width:290px">
                                <div id="kpr-grid"></div>
                                <div id="kpr-grid-tb" style="padding:5px">
                                    <div id="kpr-btn-add"></div>
                                    <div id="kpr-btn-save"></div>
                                    <div id="kpr-btn-cancel"></div>
                                    <div id="kpr-btn-del"></div>
                                </div>
                            </div>
                            <div data-options="region:'center',split:false,border:false"
                                style="background-color:#8ae0ed;">
                                <div style="padding:5px;display: flex;justify-content: end; border-left-width:1px" class="datagrid-toolbar">
                                    <div id="kpr-btn-detail"></div>
                                </div>
                                <div style="padding:20px 10px">
                                    <div style="padding-bottom:4px"><input id="kpr-form-id">&nbsp;&nbsp;
                                                Tgl. periksa&nbsp;<input id="kpr-form-tgperiksa"></div>
                                    <div style="padding-bottom:4px"><input id="kpr-form-anamnesa"></div>
                                    <div style="padding-bottom:4px"><input id="kpr-form-tb">
                                                &nbsp;cm&nbsp;<input id="kpr-form-bb">&nbsp;kg</div>
                                    <div style="padding-bottom:4px"><input id="kpr-form-nafas">&nbsp;
                                                x per menit
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                Tensi&nbsp;<input id="kpr-form-sistolik">&nbsp;/&nbsp;
                                                <input id="kpr-form-diastolik">&nbsp;mmHg
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                Suhu&nbsp;<input id="kpr-form-suhu">&nbsp;ºC</div>
                                    <div style="padding-bottom:4px"><input id="kpr-form-saturasi">&nbsp;%</div>
                                    <div style="padding-bottom:4px"><input id="kpr-form-diagnosa"></div>
                                    <div id="div-bidan" style="padding-bottom:4px"><input id="kpr-form-kebidanan"></div>
                                    <div style="padding-bottom:4px"><input id="kpr-form-penyakit"></div>
                                    <div style="padding-bottom:4px"><input id="kpr-form-prognosa"></div>
                                    <div style="padding-bottom:4px"><input id="kpr-form-terapi"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div title="Biaya" style="padding:0px">
                        <div id="kbi-grid"></div>
                        <div id="kbi-grid-tb" style="padding:5px">
                            <div id="kbi-btn-add"></div>
                            <div id="kbi-btn-save"></div>
                            <div id="kbi-btn-cancel"></div>
                            <div id="kbi-btn-del"></div>
                        </div>
                    </div>
                    <div title="Evaluasi Dokter" style="padding:0px">
                        <div class="easyui-layout"
                            data-options="fit:true,border:true,animate:false">
                            <div data-options="region:'west',border:false"
                                style="width:290px">
                                <div id="ked-grid"></div>
                                <div id="ked-grid-tb" style="padding:5px">
                                    <div id="ked-btn-add"></div>
                                    <div id="ked-btn-save"></div>
                                    <div id="ked-btn-cancel"></div>
                                    <div id="ked-btn-del"></div>
                                </div>
                            </div>
                            <div data-options="region:'center',split:false,border:false"
                                style="background-color:#8ae0ed;padding:10px">
                                <div style="padding-bottom:4px"><input id="ked-form-id">&nbsp;&nbsp;
                                            Tgl. periksa&nbsp;<input id="ked-form-tgperiksa"></div>
                                <div style="padding-bottom:4px"><input id="ked-form-dpjp"></div>
                                <div style="padding-bottom:4px"><input id="ked-form-s"></div>
                                <div style="padding-bottom:4px"><input id="ked-form-o"></div>
                                <div style="padding-bottom:4px"><input id="ked-form-a"></div>
                                <div style="padding-bottom:4px"><input id="ked-form-p"></div>
                            </div>
                        </div>
                    </div>
                    <div title="Evaluasi Perawat" style="padding:0px">
                        <div class="easyui-layout"
                            data-options="fit:true,border:true,animate:false">
                            <div data-options="region:'west',border:false"
                                style="width:290px">
                                <div id="kep-grid"></div>
                                <div id="kep-grid-tb" style="padding:5px">
                                    <div id="kep-btn-add"></div>
                                    <div id="kep-btn-save"></div>
                                    <div id="kep-btn-cancel"></div>
                                    <div id="kep-btn-del"></div>
                                </div>
                            </div>
                            <div data-options="region:'center',split:false,border:false"
                                style="background-color:#8ae0ed;padding:10px">
                                <div style="padding-bottom:4px"><input id="kep-form-id">&nbsp;&nbsp;
                                            Tgl. periksa&nbsp;<input id="kep-form-tgperiksa"></div>
                                <div style="padding-bottom:4px"><input id="kep-form-dpjp"></div>
                                <div style="padding-bottom:4px"><input id="kep-form-konsul"></div>
                                <div style="padding-bottom:4px"><input id="kep-form-s"></div>
                                <div style="padding-bottom:4px"><input id="kep-form-o"></div>
                                <div style="padding-bottom:4px"><input id="kep-form-a"></div>
                                <div style="padding-bottom:4px"><input id="kep-form-p"></div>
                            </div>
                        </div>
                    </div>
                    <div title="Laboratorium" style="padding:0px">
                        <div class="easyui-layout"
                            data-options="fit:true,border:true,animate:false">
                            <div data-options="region:'center',split:false,border:false"
                                style="background-color:#8ae0ed;padding:10px" id="kla-container">
                                <button id="labrefresh">Refresh</button>
                                <button id="labsimpan">Simpan</button>
                                <div>
                                    <table id="klb-fields-panel"
                                        width="100%"
                                        height="0%"
                                        style="font-size:14px;padding:3px">
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div id="yan-suketmti-dlg"></div>
                <div id="yan-suketskt-dlg"></div>
                <div id="yan-suketsht-dlg"></div>
                <div id="yan-foto-dlg"></div>
                <div id="yan-mti-dlg"></div>
                <div id="yan-skt-dlg"></div>
                <div id="yan-ppi-dlg"></div>
                <div id="kbi-obt-dlg"></div>
                <div id="kpr-obgyn-dlg"></div>
                <div id="kpr-dental-dlg"></div>
            </div>
        </div>
    </div>
    <div data-options="region:'east',
        split:false,
        animate:false,
        collapsed:true,
        hideCollapsedContent:false,
        border:false"
        title="Data Rujukan"
        id="yan-rujukan-region"
        style="width:400px;padding:0 0 0 1px;background-color:#95b8e7">
        <table id="kpr-data-rujukan" width="100%" height="100%"
            style="font-size:14px;background-color:#eeeeee" cellspacing="0"></table>
    </div>
    <div id="yan-repdiv"></div>
    <div id="yan-replabdiv"></div>
    <div id="yan-repprintlabdiv"></div>
</div>