<script type="text/javascript" src="<?= base_url('easyui/treegrid-dnd.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('js/custom/coa_view.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('js/custom/jurnal_view.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('js/custom/utility.js') ?>"></script>
<style type="text/css">
    .textbox-readonly .textbox-text {
        background:#fff6c1;
        color:#aaaaaa;
    }
    .datagrid-row {
        height:25px;
    }
    .accrep-h {
        padding: 3px 6px 3px 6px;
        font-weight: bold;
        font-size: 14px;
        vertical-align: middle;
        border-top: 1px solid black;  
        border-bottom: 1px solid black;
        white-space: nowrap;
        background-color: #eeeeee; 
    }
    .accrep-r {
        padding: 3px 6px 3px 6px;
        font-size: 14px;
        vertical-align: middle;
        white-space: nowrap;
    }
</style>
<div class="easyui-layout" style="width:100%;height:100%;">
    <div data-options="region:'center',border:false"
        title="Laporan Accounting">
        <div class="easyui-tabs"
            data-options="border:false,fit:true,tabPosition:'bottom'"
            style="padding:0px">
            <div title="Jurnal">
                <div class="easyui-layout" data-options="fit:true"
                    style="background-color:#ffffff;padding:0px">
                    <div data-options="region:'north',split:false,border:false"
                        style="background-color:#8ae0ed;height:41px;padding:4px 10px 4px 10px">
                        Tanggal:&nbsp;
                        <input id="jurnal-form-tgldari">&nbsp;s/d&nbsp;
                        <input id="jurnal-form-tglke">&nbsp;
                        <div id="jurnal-btn-create"></div>&nbsp;
                        <div id="jurnal-btn-pdf"></div>
                    </div>
                    <div data-options="region:'center',border:false"
                        style="background-color:#e0ecff;padding:30px">
                        <div id="jurnal-rep"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div data-options="region:'east',
        split:false,
        hideCollapsedContent:false,
        border:false,
        collapsed:true" title="Akun GL"
        style="width:500px">
        <div id="coa-grid"></div>
        <div id="coa-grid-tb" style="padding:5px;position:relative">
            <div class="easyui-linkbutton" id="coa-btn-newclass">Tambah Kelas Akun</div>
            <div class="easyui-linkbutton" id="coa-btn-reload">Reload</div>
            <div style="position:absolute;right:5px;bottom:5px;
                font-size:75%;color:#727272">(grid right click : context-menu)</div>
        </div>
        <div id="coa-menu" class="easyui-menu" style="width:120px;">
            <div id="coa-mnu-addsub" data-options="iconCls:'icon-add'">Tambah</div>
            <div id="coa-mnu-delsub" data-options="iconCls:'icon-remove'">Hapus</div>
        </div>
    </div>
</div>