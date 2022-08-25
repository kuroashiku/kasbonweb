<script type="text/javascript" src="<?= base_url('js/custom/loket_view.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('css/style-man.css') ?>">
<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'north',split:false,border:false"
        style="height:290px;background-color:#8ae0ed;padding:5px">
        <table width="100%" height="0%">
            <tr>
                <td width="0%" style="vertical-align:top">
                    <table width="100%" height="0%" style="font-size:14px">
                        <?= formControl('ID','lkt-form-id') ?>
                        <tr height="0%">
                            <td style="white-space:nowrap" width="0%"
                                style="vertical-align:top">No. Registrasi</td>
                            <td style="white-space:nowrap" width="100%">
                                <input id="lkt-form-noregistrasi">
                                <input id="lkt-form-status">
                            </td>
                        </tr>
                        <?= formControl('Layanan (Klinik)','lkt-form-layanan') ?>
                        <?= formControl('Pasien hewan','lkt-form-hewan') ?>
                        <tr height="0%">
                            <td style="white-space:nowrap" width="0%" style="vertical-align:top">
                                <span id="lkt-form-manusia-label">Pasien:</span></td>
                            <td style="white-space:nowrap" width="100%">
                                <input id="lkt-form-manusia">
                            </td>
                        </tr>
                        <?= formControl('Tgl. masuk','lkt-form-tgcheckin') ?>
                        <?= formControl('No. antrian','lkt-form-noantrian') ?>
                        <?= formLabel('<span style="color:#003f74">
                                Diketik dari struk (optional)</span>') ?>
                        <?= formControl('Dokter/DPJP','lkt-form-dokter') ?>
                        <?= formControl('','lkt-form-jeniskelamin') ?>
                    </table>
                </td>
                <td width="0%" style="vertical-align:top">
                    <table width="100%" height="0%" style="font-size:14px">
                        <?= formControl('Keperluan','lkt-form-keperluan') ?>
                        <?= formControl('Pembayaran','lkt-form-pembayaran') ?>
                        <?= formControl('Mitra asuransi','lkt-form-mitra') ?>
                        <?= formControl('No kartu Askes','lkt-form-noasuransi') ?>
                        <?= formControl('Rujukan','lkt-form-rujukan') ?>
                    </table>
                </td>
                <td width="0%" style="vertical-align:top">
                    <table width="100%" height="0%" style="font-size:14px">
                        <?= formControl('No. rujukan','lkt-form-rusukno') ?>
                        <?= formControl('No. rujukan Askes','lkt-form-rusuknomitra') ?>
                        <?= formControl('Anamnesa','lkt-form-rusukanamnesa') ?>
                        <?= formControl('Pem. fisik','lkt-form-rusukcekfisik') ?>
                        <?= formControl('Pem. penunjang','lkt-form-rusukcekpenunjang') ?>
                        <?= formControl('Diagnosis (ICD X)','lkt-form-rusukpenyakit') ?>
                    </table>
                </td>
                <td width="100%" style="vertical-align:top">
                    <table width="100%" height="0%" style="font-size:14px">
                        <?= formControl('Tanggal rujukan','lkt-form-rusuktg') ?>
                        <?= formControl('Tindakan','lkt-form-rusuktindakan') ?>
                        <?= formControl('Alasan dirujuk','lkt-form-rusukalasan') ?>
                        <?= formControl('Diterima oleh','lkt-form-rusukpenerima') ?>
                        <?= formLabel('Dikirimkan oleh') ?>
                        <?= formControl('Nama','lkt-form-rusukpengirim') ?>
                        <?= formControl('Unit/ Institusi','lkt-form-rusukunitpengirim') ?>
                        <?= formControl('Telpon','lkt-form-rusuktelponpengirim') ?>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <div data-options="region:'center',border:false">
        <div id="lkt-grid"></div>
        <div id="lkt-grid-tb" style="padding:5px">
            <div id="lkt-btn-add"></div>
            <div id="lkt-btn-save"></div>
            <div id="lkt-btn-cancel"></div>
            <div id="lkt-btn-del"></div>
            <div id="lkt-btn-idpas"></div>
            <div id="lkt-btn-man"></div>
            <div id="lkt-btn-rekap"></div>
            <div id="lkt-search"></div>
            <div id="lkt-chk-selesai"></div>
            <!-- object untuk memunculkan dialog -->
            <div id="lkt-repidpas-dlg"></div>
            <div id="lkt-rekap-dlg"></div>
        </div>
    </div>
</div>
