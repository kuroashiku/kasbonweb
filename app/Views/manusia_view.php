<script type="text/javascript" src="<?= base_url('js/custom/manusia_view.js') ?>"></script>
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
        style="height:280px;background-color:#8ae0ed;padding:5px">
        <table width="100%" height="0%">
            <tr>
                <td width="0%" style="vertical-align:top">
                    <table width="100%" height="0%" style="font-size:14px">
                        <tr height="0%">
                        <input id="man-form-id">
                        </tr>
                        <?= formControlCombine('No. RM','man-form-norm','Nama','man-form-nama') ?>
                        <?= formControl('Tempat lahir','man-form-kotalahir') ?>
                        <?= formControlCombine('Tgl. lahir','man-form-tglahir','Gender','man-form-kelamin') ?>
                        <?= formControl('Alamat KTP','man-form-alamatktp') ?>
                        <?= formControl('Alamat sekarang','man-form-alamatskrng') ?>
                        <?= formControl('Kota','man-form-area') ?>
                        <?= formControl('Desa/Kelurahan','man-form-area-desa') ?>
                        <tr height="0%">
                            <td style="white-space:nowrap" width="0%">Dusun</td>
                            <td style="white-space:nowrap" width="100%">
                                <input id="man-form-dusun"> RW&nbsp;<input id="man-form-rw">
                                RT&nbsp;<input id="man-form-rt">
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="0%" style="vertical-align:top">
                    <table width="100%" height="0%" style="font-size:14px">
                        <?= formControl('Kode pos','man-form-kodepos') ?>
                        <?= formControl('Pembayaran','man-form-pembayaran') ?>
                        <?= formControl('No. asuransi','man-form-noasuransi') ?>
                        <tr height="0%" style="font-size:14px">
                            <td></td>
                            <td id="man-bpjs-loading">
                                <span class="spinner-border spinner-border-sm text-info mr-2"></span>
                                <span class="text-info">Loading BPJS Data</span>
                            </td>
                        </tr>
                        <?= formControl('NIK','man-form-nik') ?>
                        <?= formControl('No. KK','man-form-nokk') ?>
                        <?= formControl('Telpon','man-form-telpon') ?>
                        <?= formControl('Gol. darah','man-form-goldarah') ?>
                        <?= formControl('RM Ibu','man-form-rmibu') ?>
                    </table>
                </td>
                <td width="100%" style="vertical-align:top">
                    <table width="100%" height="0%" style="font-size:14px">
                        <?= formControl('Kebangsaan','man-form-kebangsaan') ?>
                        <?= formControl('Suku bangsa','man-form-sukubangsa') ?>
                        <?= formControl('Bahasa','man-form-bahasa') ?>
                        <?= formControl('Pekerjaan','man-form-pekerjaan') ?>
                        <?= formControl('Pendidikan','man-form-pendakhir') ?>
                        <?= formControl('Status nikah','man-form-statusnikah') ?>
                    </table>
                </td>
                <td width="0%" style="vertical-align:top">
                    <table width="100%" height="0%" style="font-size:14px">
                        <?= formControl('Agama','man-form-agama') ?>
                        <?= newFormLabel('Famili yang bisa dihubungi saat kondisi darurat','man-ket','100') ?>
                        <?= formControl('Nama','man-form-namafam') ?>
                        <?= formControl('Hubungan kerabat','man-form-hubfam') ?>
                        <?= formControl('Alamat','man-form-alamatfam') ?>
                        <?= formControl('Telpon','man-form-telpfam') ?>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <div data-options="region:'center',border:false">
        <div id="man-grid"></div>
        <div id="man-grid-tb" style="padding:5px">
            <div id="man-btn-add"></div>
            <div id="man-btn-save"></div>
            <div id="man-btn-cancel"></div>
            <div id="man-btn-del"></div>
            <div id="man-btn-kartupas"></div>
            <div style="float:right">
                <table><tr> <td id="man-pencarian"> Pencarian berdasarkan </td> <td> &nbsp;&nbsp;  </td> <td> <div id="man-search-by"></div> </td>
                <td> <div id="man-search"></div> </td> 
                <td> <div id="man-datesearch"></div> </td> <td> &nbsp;<div id="man-datesearchok"></div> </td> </tr> </table>
            </div>
        </div>
        <div id="man-menu" class="easyui-menu" style="width:240px;"></div>
        <div id="man-repkartu-dlg"></div>
    </div>
</div>