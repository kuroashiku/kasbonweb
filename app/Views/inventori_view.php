<script type="text/javascript" src="<?= base_url('js/custom/obat_view.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('js/custom/po_view.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('js/custom/receive_view.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('js/custom/utility.js') ?>"></script>
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
</style>
<div class="easyui-layout"
    data-options="fit:true,border:false">
    <div data-options="region:'center',border:false"
        title="Modul Obat">
        <div id="obt-grid"></div>
        <div id="obt-grid-tb" style="padding:5px;position:relative; display:flex; justify-content: space-between;">
            <div style="display:flex; align-items:center; margin-right:8px">
                <div>Cari Obat&nbsp;&nbsp;</div>
                <div>
                    <input id="obt-search"/>
                </div>
            </div>
            <div style="display:flex; align-items:center">
                <div>Kategori&nbsp;&nbsp;</div>
                <div>
                    <input id="obt-filter-ctg">
                </div>
            </div>
        </div>
        <div id="obt-menu" class="easyui-menu" style="width:120px;">
            <div data-options="id:'obt-mnu-add',iconCls:'icon-add'">Tambahkan ke PO</div>
            <div data-options="id:'obt-mnu-edit',iconCls:'icon-edit'">Edit</div>
        </div>
    </div>
    <div data-options="region:'east',border:false,split:false"
        title="Inventori" style="width:800px;padding:0px">
        <div class="easyui-tabs" id="inv-tab"
            data-options="border:false,fit:true,tabPosition:'bottom'">
            <div title="Permintaan Pembelian (PO)" style="padding:5px;background-color:#8ae0ed">
                <div class="easyui-layout" data-options="fit:true">
                    <div data-options="region:'north',split:false,border:false"
                        style="height:75px;background-color:#8ae0ed">
                        <table width="100%" height="0%">
                            <tr height="0%">
                                <td>
                                    <table width="100%"
                                        style="font-size:14px;
                                        padding:3px">
                                        <tr>
                                            <td width="0%">ID:</td>
                                            <td width="0%"><input id="po-form-id"></td>
                                            <td width="0%">Supplier:</td>
                                            <td width="0%"><input id="po-form-supplier"></td>
                                            <td width="0%">Status:</td>
                                            <td width="100%"><input id="po-form-status"></td>
                                        </tr>
                                        <tr>
                                            <td style="white-space:nowrap">Nomor PO:</td>
                                            <td><input id="po-form-kode"></td>
                                            <td style="white-space:nowrap">Tgl Order:</td>
                                            <td><input id="po-form-tgorder"></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div data-options="region:'center',border:false">
                        <div id="po-grid"></div>
                        <div id="po-grid-tb" style="padding:5px;position:relative">
                            <div class="easyui-linkbutton" id="po-btn-add">Tambah</div>
                            <div class="easyui-linkbutton" id="po-btn-save">Simpan</div>
                            <div class="easyui-linkbutton" id="po-btn-cancel">Batal</div>
                            <div class="easyui-linkbutton" id="po-btn-del">Hapus</div>
                            <div id="po-search"></div>
                            <div style="position:absolute;right:5px;bottom:5px;
                                font-size:75%;color:#727272">(grid right click : context-menu)</div>
                        </div>
                        <div id="po-menu" class="easyui-menu" style="width:240px;">
                            <div data-options="id:'po-mnu-rcv',iconCls:'icon-add'">Penerimaan (create receivement)</div>
                        </div>
                    </div>
                    <div id="poi-region" data-options="region:'south',split:true,border:false"
                        title="Daftar item permintaan pembelian (PO items)" style="height:200px">
                        <div id="poi-grid"></div>
                        <div id="poi-grid-tb" style="padding:5px">
                            Harga:&nbsp;<input id="poi-form-harga">&nbsp;Qty:&nbsp;
                            <input id="poi-form-qty">
                            <div class="easyui-linkbutton" id="poi-btn-save">Simpan</div>
                            <div class="easyui-linkbutton" id="poi-btn-cancel">Batal</div>
                            <div class="easyui-linkbutton" id="poi-btn-del">Hapus</div>
                        </div>
                    </div>
                </div>
            </div>
            <div title="Penerimaan Barang (Receiving)" style="padding:5px;background-color:#8ae0ed">
                <div class="easyui-layout" data-options="fit:true">
                    <div data-options="region:'north',split:false,border:false"
                        style="height:75px;background-color:#8ae0ed">
                        <table width="100%" height="0%">
                            <tr>
                                <td width="0%" style="vertical-align:top">
                                    <table width="100%" height="0%" style="font-size:14px">
                                        <?= formControl('ID','rcv-form-id') ?>
                                        <?= formControl('No. receive','rcv-form-kode') ?>
                                    </table>
                                </td>
                                <td width="0%" style="vertical-align:top">
                                    <table width="100%" height="0%" style="font-size:14px">
                                        <?= formControl('Tgl. diterima','rcv-form-tgterima') ?>
                                        <?= formControl('Tgl. lunas','rcv-form-tglunas') ?>
                                    </table>
                                </td>
                                <td width="100%" style="vertical-align:top">
                                    <table width="100%" height="0%" style="font-size:14px">
                                        <?= formControl('Kas/Bank','rcv-form-kasbank') ?>
                                        <?= formControl('Status','rcv-form-status') ?>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div data-options="region:'center',border:false">
                        <div id="rcv-grid"></div>
                        <div id="rcv-grid-tb" style="padding:5px;position:relative">
                            <div class="easyui-linkbutton" id="rcv-btn-save">Simpan</div>
                            <div class="easyui-linkbutton" id="rcv-btn-cancel">Batal</div>
                            <div class="easyui-linkbutton" id="rcv-btn-del">Hapus</div>
                            <div id="rcv-search"></div>
                            <div style="position:absolute;right:5px;bottom:5px;
                                font-size:75%;color:#727272">(grid right click : context-menu)</div>
                        </div>
                        <div id="rcv-menu" class="easyui-menu" style="width:255px;">
                            <div data-options="id:'rcv-mnu-item',iconCls:'icon-add'">Tambahkan item penerimaan barang</div>
                        </div>
                    </div>
                    <div id="rcvi-region" data-options="region:'south',split:true,border:false"
                        title="Daftar item penerimaan barang (Receive items)" style="height:200px">
                        <div id="rcvi-grid"></div>
                        <div id="rcvi-grid-tb" style="padding:5px;position:relative">
                            Harga:&nbsp;<input id="rcvi-form-harga">&nbsp;Qty:&nbsp;
                            <input id="rcvi-form-qty">
                            <div class="easyui-linkbutton" id="rcvi-btn-save">Simpan</div>
                            <div class="easyui-linkbutton" id="rcvi-btn-cancel">Batal</div>
                            <div style="position:absolute;right:5px;bottom:5px;
                                font-size:75%;color:#727272">(grid right click : context-menu)</div>
                        </div>
                        <div id="rcvi-menu" class="easyui-menu" style="width:150px;">
                            <div data-options="id:'rcvi-mnu-item',iconCls:'icon-add'">Hapus item</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>