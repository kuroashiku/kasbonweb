<script type="text/javascript" src="<?= base_url('js/custom/hewan_view.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('js/custom/utility.js') ?>"></script>
<style type="text/css">
    .textbox-readonly .textbox-text {
        background:#fff6c1;
        color:#aaaaaa;
    }
    .datagrid-row {
        height:25px;
    }
</style>
<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'north',split:false,border:false"
        style="height:80px;background-color:#8ae0ed;padding:5px">
        <table width="100%" height="0%">
            <tr>
                <td width="0%">
                    <table width="100%" height="100%" style="font-size:14px">
                        <?= formControl('ID:','hwn-form-id') ?>
                        <?= formControl('No. RM:','hwn-form-norm') ?>
                    </table>
                </td>
                <td width="0%">
                    <table width="100%" height="100%" style="font-size:14px">
                        <?= formControl('Nama hewan:','hwn-form-hewan') ?>
                        <?= formControl('Nama pemilik:','hwn-form-manusia') ?>
                    </table>
                </td>
                <td width="0%">
                    <table width="100%" height="100%" style="font-size:14px">
                        <?= formControl('Tgl. lahir:','hwn-form-tglahir') ?>
                        <?= formControl('Kelamin:','hwn-form-kelamin') ?>
                    </table>
                </td>
                <td width="100%">
                    <table width="100%" height="100%" style="font-size:14px">
                        <?= formControl('Spesies:','hwn-form-spesies') ?>
                        <?= formControl('Ras:','hwn-form-ras') ?>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <div data-options="region:'center',border:false">
        <div id="hwn-grid"></div>
        <div id="hwn-grid-tb" style="padding:5px">
            <a href="#" class="easyui-linkbutton" id="hwn-btn-add">Tambah</a>
            <a href="#" class="easyui-linkbutton" id="hwn-btn-save">Simpan</a>
            <a href="#" class="easyui-linkbutton" id="hwn-btn-cancel">Batal</a>
            <a href="#" class="easyui-linkbutton" id="hwn-btn-del">Hapus</a>
            <div id="hwn-search"></div>
        </div>
        <div id="hwn-menu" class="easyui-menu" style="width:240px;"></div>
    </div>
</div>